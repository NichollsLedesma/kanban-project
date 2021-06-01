<?php

namespace frontend\controllers;

use common\jobs\JobTest;
use common\models\Board;
use common\models\Entity;
use common\models\User;
use common\models\UserEntity;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Yii;
use yii\elasticsearch\QueryBuilder;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\Controller;
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
        $this->layout = "kanban";
        // $search = Yii::$app->request->post('search');

        // $board = ($search) ?
        //     $this->getDataDump($search) :
        //     $this->getDump();

        return $this->render('board', [
            'board' => $this->getDump(),
        ]);
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
