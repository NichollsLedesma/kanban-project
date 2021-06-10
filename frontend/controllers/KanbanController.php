<?php

namespace frontend\controllers;

use common\jobs\JobTest;
use common\models\Board;
use common\models\BoardRepository;
use common\models\Card;
use common\models\CardRepository;
use common\models\Column;
use common\models\CreateColumnForm;
use common\models\UpdateColumnForm;
use common\models\User;
use common\models\elastic\Board as ElasticBoard;
use common\models\elastic\Card as ElasticCard;
use common\models\elastic\ElasticHelper;
use common\models\Entity;
use common\models\UserBoard;
use common\models\UserEntity;
use common\widgets\BoardCard\BoardCard;
use frontend\models\CreateCardForm;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;
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
        $entities = Entity::find()
            ->select(["id", "name", "uuid"])
            ->where([
                "in", "id", UserEntity::find()->select(["entity_id"])
                    ->where([
                        "user_id" => Yii::$app->getUser()->getId(),
                    ]),
            ])
            ->with([
                "boards" => function ($query) {
                    $query->where([
                        "in", "id", UserBoard::find()->select(["board_id"])
                            ->where([
                                "user_id" => Yii::$app->getUser()->getId(),
                            ])
                    ]);
                }
            ])
            ->all();

        return $this->render('index', [
            "entities" => $entities,
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
            $userCard = CardRepository::getUserBoardCardByUuid(Yii::$app->user->id, $this->request->post('card'));

            $column = Column::find()->select(['id'])->where(['uuid' => $this->request->post('column')])->limit(1)->one();
            if ($userBoard === null || $column === null) {
                throw new NotFoundHttpException('card  or column not found');
            }
            CardRepository::reArrageByCardId($userCard->id, $this->request->post('order'), $column->id);
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

        if ($this->request->isPjax && $this->request->get('updateColumn')) {
            $uuidColumn = $this->request->get('updateColumn');
            $updateColumnModel = new UpdateColumnForm(['scenario' => Column::SCENARIO_AJAX_UPDATE]);
            $updateColumnModel = $updateColumnModel->find()->where(['uuid' => $uuidColumn])->one();
            if ($this->request->isPost && $updateColumnModel->load($this->request->post()) && $updateColumnModel->validate()) {
                $updateColumnModel->save();
                $updateColumnModel->columnUpdated(Url::to(['kanban/board', 'uuid' => $uuid]));
                $this->response->headers->set('X-PJAX-URL', Url::to(['/kanban/board', 'uuid' => $uuid]));
                unset($updateColumnModel);
            }
        }

        $board = Board::find()->where(["uuid" => $uuid])->limit(1)->one();

        return $this->render('board', [
            'boardName' => $board->title,
            'members' => $board->users,
            'boardUuid' => $uuid,
            'boardColumns' => $boardColumns,
            'newCardModel' => $newCardModel ?? null,
            'newColumnModel' => $newColumnModel ?? null,
            'updateColumnModel' => $updateColumnModel ?? null,
        ]);
    }

    public function actionUpdateColumnOrder($uuid)
    {
        foreach (Yii::$app->request->post('columns') as $key => $value) {
            Column::updateAll(['order' => $key], ['uuid' => $value]);
        }
        $topic = Url::to(['kanban/board', 'uuid' => $uuid]);
        $response = array(
            'type' => 'Column ReOrder',
        );
        Yii::$app->mqtt->sendMessage($topic, $response);
    }

    public function actionArchiveColumn($uuid)
    {
        $column = Column::find()->where(['uuid' => $uuid])->limit(1)->one();

        if (!$column) {
            return false;
        }

        $topic = Url::to(['kanban/board', 'uuid' => $column->board->uuid]);
        $response = array(
            'type' => 'Column Removed',
        );
        Yii::$app->mqtt->sendMessage($topic, $response);

        return $column->delete();
    }

    public function actionCardUpdate($uuid, $boardUuid)
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
            $obj = ['type' => 'card', 'action' => 'remove', 'params' => ['cardId' => $userCardModel->uuid]];

            Yii::$app->mqtt->sendMessage(Url::to(['kanban/board', 'uuid' => $boardUuid]), $obj);

            Yii::$app->session->setFlash('deleted', true);
        }
        if ($this->request->isPost && !$this->request->post('DeleteCardForm') && $userCardModel->load($this->request->post()) && $userCardModel->validate() && $userCardModel->save()) {
            $obj = ['type' => 'card', 'action' => 'update', 'params' => ['cardId' => $userCardModel->uuid, 'title' => $userCardModel->title, 'description' => $userCardModel->description, 'color' => $userCardModel->color]];

            Yii::$app->mqtt->sendMessage(Url::to(['kanban/board', 'uuid' => $boardUuid]), $obj);
            Yii::$app->session->setFlash('updated', true);
        }
        return $this->renderAjax('_cardUpdate', ['model' => $userCardModel, 'deleteModel' => $deleteCardModel]);
    }

    public function actionGet($uuid)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $search = Yii::$app->request->get('query');
        $board = Board::find()->where(["uuid" => $uuid])->one();

        if (!$board) {
            return [];
        }

        return ElasticBoard::getFiltredCards($board->id, $search);
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
