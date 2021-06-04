<?php

namespace frontend\controllers;

use common\jobs\JobTest;
use common\models\Board;
use common\models\BoardRepository;
use common\models\Card;
use common\models\CardRepository;
use common\models\Column;
use common\models\CreateColumnForm;
use common\models\elastic\Card as ElasticCard;
use common\models\elastic\ElasticHelper;
use common\widgets\BoardCard\BoardCard;
use frontend\models\CreateCardForm;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class KanbanController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                "class" => AccessControl::class,
                "only" => ['index', 'board', 'logout'],
                "rules" => [
                    [
                        'allow' => true,
                        'roles' => ["@"],
                    ]
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $boards = Board::find()
                ->where(["owner_id" => Yii::$app->getUser()->getId()])
                ->andWhere(['is_deleted' => 0])
                ->all();
        $entities = ArrayHelper::map(
                        Yii::$app->getUser()->getIdentity()->entities,
                        'id',
                        'name'
        );

        return $this->render('index', [
                    "boards" => $boards,
                    "entities" => $entities
        ]);
    }

    public function actionBoard($uuid)
    {
        $userBoard = BoardRepository::getUserBoardByUuid(Yii::$app->user->id, $uuid);
        $boardColumns = Column::find()->where(['board_id' => $userBoard->select(['id'])->limit(1)])->orderBy(['order' => 'ASC']);
        if ($userBoard->count() == 0) {
            throw new NotFoundHttpException('board not found');
        }
        if ($this->request->isPost && $this->request->isAjax && $this->request->get('changeOrder')) {
            $obj = ['type' => 'card', 'action' => 'move', 'params' => ['columnId' => $this->request->post('column'), 'cardId' => $this->request->post('card'), 'order' => $this->request->post('order')]];
            Yii::$app->mqtt->sendMessage(Url::to(['kanban/board', 'uuid' => $uuid]), $obj);
        }

        if ($this->request->isPjax && $this->request->get('addCard')) {

            $newCardModel = new CreateCardForm(['scenario' => Card::SCENARIO_AJAX_CREATE]);
            $columnUuid = clone $boardColumns;
            $columnUuid->filterWhere(['uuid' => $this->request->get('addCard')])->select(['id'])->limit(1);
            if ($columnUuid->count() == 0) {
                throw new NotFoundHttpException(printf("Column %s doesn't exists", $this->request->get('addCard')));
            }

            $newCardModel->column_id = $columnUuid->scalar();
            if ($this->request->isPost && $newCardModel->load($this->request->post()) && $newCardModel->validate() && $newCardModel->createCard()) {
                $obj = ['type' => 'card', 'action' => 'new', 'params' => ['columnId' => $this->request->get('addCard'), 'order' => 'last', 'html' => BoardCard::widget(['id' => $newCardModel->uuid, 'title' => $newCardModel->title, 'content' => $newCardModel->description])]];
                Yii::$app->mqtt->sendMessage(Url::to(['kanban/board', 'uuid' => $uuid]), $obj);
                $this->response->headers->set('X-PJAX-URL', Url::to(['/kanban/board', 'uuid' => $uuid]));
                unset($newCardModel);
            }
        }

        if ($this->request->isPjax && $this->request->get('addColumn')) {

            $newColumnModel = new CreateColumnForm(['scenario' => Column::SCENARIO_AJAX_CREATE]);
            $newColumnModel->board_id = $userBoard->select(['id'])->limit(1)->one()->id;
            if ($this->request->isPost && $newColumnModel->load($this->request->post()) && $newColumnModel->validate() && $newColumnModel->createColumn()) {
                $newColumnModel->columnCreated(Url::to(['kanban/board', 'uuid' => $uuid]));
                $this->response->headers->set('X-PJAX-URL', Url::to(['/kanban/board', 'uuid' => $uuid]));
                unset($newColumnModel);
            }
        }

        $this->layout = "kanban";
        $board = Board::find()->where(["uuid" => $uuid])->limit(1)->one();

        return $this->render('board', [
                    'boardName' => $board->title,
                    'boardUuid' => $uuid,
                    'boardColumns' => $boardColumns,
                    'newCardModel' => $newCardModel ?? null,
                    'newColumnModel' => $newColumnModel ?? null,
        ]);
    }

    public function actionCardUpdate($uuid)
    {
        if (!$this->request->isAjax) {
            throw new NotFoundHttpException('not found');
        }
        $userCardModel = CardRepository::getUserBoardCardByUuid(Yii::$app->user->id, $uuid);
        if ($userCardModel === null) {
            throw new NotFoundHttpException('card not found');
        }
        $deleteCardModel = new \frontend\models\DeleteCardForm();
        if ($this->request->isPost && $this->request->post('DeleteCardForm') && $deleteCardModel->load($this->request->post()) && $deleteCardModel->validate()) {
            $userCardModel->delete();
        }
        if ($this->request->isPost && !$this->request->post('DeleteCardForm') && $userCardModel->load($this->request->post()) && $userCardModel->validate() && $userCardModel->save()) {
            Yii::$app->session->setFlash('updated', true);
        }
        return $this->renderAjax('_cardUpdate', ['model' => $userCardModel, 'deleteModel' => $deleteCardModel]);
    }

    public function actionGet($uuid)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $search = Yii::$app->request->get('query');
        // $search = Yii::$app->request->get('query');
        // $data = ElasticHelper::search(ElasticCard::class, ["title" => $search]);
        $board = Board::find()->where(["uuid" => $uuid])->one();

        if (!$board) {
            return [];
        }

        $data = ElasticCard::find()->query([
                    "bool" => [
                        "filter" => [
                            'match' => ["title" => $search],
                        ],
                        "must" => [
                            'match' => ["board_id" => $board->id],
                        ],
                    ]
                ])->asArray()->all();

        $ret = [];
        foreach ($data as $item) {
            $itemSource = $item['_source'];
            $ret[] = [
                "id" => $itemSource['uuid'],
                "value" => $itemSource['title'],
                "label" => $itemSource['title'],
            ];
        }

        return $ret;
        // ArrayHelper::getColumn(
        //     $data,
        //     function ($item) {
        //         $itemSource = $item['_source'];
        //         return [
        //             "id" => $itemSource['id'],
        //             "value" => $itemSource['title'],
        //             "label" => $itemSource['title'],
        //         ];
        //     }
        // );
        // $select = ['username as value', 'username as  label', 'id as id'];
        // return User::find()
        //     ->select($select)
        //     ->asArray()
        //     ->all();
    }

    public function actionGetOne($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return ElasticHelper::search(ElasticCard::class, ["uuid" => $id]);
    }

    public function actionMove()
    {
        $id = Yii::$app->queue->push(
                new JobTest(
                        [
                    "message" => "Hi job"
                        ]
                )
        );
    }

}
