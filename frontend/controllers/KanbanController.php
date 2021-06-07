<?php

namespace frontend\controllers;

use Yii;
use common\jobs\JobTest;
use common\models\Board;
use common\models\BoardRepository;
use common\models\Card;
use common\models\Column;
use common\models\CreateColumnForm;
use common\models\UpdateColumnForm;
use common\models\User;
use frontend\models\CreateCardForm;
use yii\elasticsearch\QueryBuilder;
use yii\filters\AccessControl;
use yii\helpers\Json;
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
        $boards = Board::find()
            ->where(["owner_id" => Yii::$app->getUser()->getId()])
            ->all();

        return $this->render('index', [
            "boards" => $boards
        ]);
    }

    public function actionBoard($uuid)
    {
        $userBoard = BoardRepository::getUserBoard(Yii::$app->getUser()->getId(), $uuid);
        $boardColumns = Column::find()->where(['board_id' => $userBoard->select(['id'])->limit(1)])->orderBy(['order' => 'ASC']);
        if ($userBoard->count() == 0) {
            throw new NotFoundHttpException('board not found');
        }

        if ($this->request->isPjax && $this->request->get('addCard')) {

            $newCardModel = new CreateCardForm(['scenario' => Card::SCENARIO_AJAX_CREATE]);
            $columnUuid = clone $boardColumns;
            $columnUuid->filterWhere(['uuid' => $this->request->get('addCard')])->select(['id'])->limit(1);
            if ($columnUuid->count() == 0) {
                throw new NotFoundHttpException(printf("Column %s doesn't exists", $this->request->get('addCard')));
            }

            $newCardModel->column_id = $columnUuid->scalar();
            if ($this->request->isPost && $newCardModel->load($this->request->post()) && $newCardModel->validate() && $newCardModel->createCard(Url::to(['kanban/board', 'uuid' => $uuid]), $this->request->get('addCard'))) {
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
            if ($this->request->isPost && $updateColumnModel->load($this->request->post()) &&$updateColumnModel->validate()) {
                $updateColumnModel->save();
                $updateColumnModel->columnUpdated(Url::to(['kanban/board', 'uuid' => $uuid]));
                $this->response->headers->set('X-PJAX-URL', Url::to(['/kanban/board', 'uuid' => $uuid]));
                unset($updateColumnModel);
            }
        }

        $this->layout = "kanban";
        // $search = Yii::$app->request->post('search');
        // $board = ($search) ?
        //     $this->getDataDump($search) :
        //     $this->getDump();

        return $this->render('board', [
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
            'type'=>'Column ReOrder',
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
            'type'=>'Column Removed',
        );
        Yii::$app->mqtt->sendMessage($topic, $response);

        return $column->delete();

    }

    public function actionGet()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $search = Yii::$app->request->get('query');
        $select = ['username as value', 'username as  label', 'id as id'];

        return User::find()
            ->select($select)
            ->asArray()
            ->all();
    }

    public function actionGetOne($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            "id" => $id,
            "name" => "task " . $id,
            "description" => "something",
        ];
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

    private function getBoardsDump()
    {
        return [
            $this->getDump(1),
            $this->getDump(2),
            $this->getDump(3),
            $this->getDump(4),
            $this->getDump(5),
            $this->getDump(6),
            $this->getDump(7),
            $this->getDump(8),
        ];
    }

    private function getDump($id = 1)
    {
        return [
            "id" => $id,
            "uuid" => "randomuuid_$id",
            "name" => "board_name_$id",
            "columns" => [
                [
                    "id" => 1,
                    "name" => "backlog",
                    "tasks" => [
                        [
                            "id" => 1,
                            "name" => "task 1",
                            "description" => "something",
                        ],
                        [
                            "id" => 2,
                            "name" => "task 2",
                            "description" => "something",
                        ],
                        [
                            "id" => 3,
                            "name" => "task 3",
                            "description" => "something",
                        ],
                        [
                            "id" => 4,
                            "name" => "task 4",
                            "description" => "something",
                        ],
                        [
                            "id" => 5,
                            "name" => "task 5",
                            "description" => "something",
                        ]
                    ]
                ],
                [
                    "id" => 2,
                    "name" => "todo",
                    "tasks" => [
                        [
                            "id" => 6,
                            "name" => "task 6",
                            "description" => "something",
                        ]
                    ]
                ],
                [
                    "id" => 3,
                    "name" => "doing",
                    "tasks" => []
                ],
                [
                    "id" => 4,
                    "name" => "done",
                    "tasks" => []
                ],
            ]
        ];
    }

    private function getDataDump($search)
    {
        return [
            "id" => 1,
            "name" => "board_name",
            "columns" => [
                [
                    "id" => 1,
                    "name" => "backlog",
                    "tasks" => [
                        [
                            "id" => 1,
                            "name" => "task 1",
                            "description" => "something",
                        ],
                    ]
                ],
                [
                    "id" => 2,
                    "name" => "todo",
                    "tasks" => [
                        [
                            "id" => 6,
                            "name" => "task 6",
                            "description" => "something",
                        ]
                    ]
                ],
                [
                    "id" => 3,
                    "name" => "doing",
                    "tasks" => []
                ],
                [
                    "id" => 4,
                    "name" => "done",
                    "tasks" => []
                ],
            ]
        ];
    }
}
