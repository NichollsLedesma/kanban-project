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
        $boards = [];
        if ( $this->request->get('uuid')) {
            $entity = Entity::findOne(["uuid" => $this->request->get('uuid')]);

            if (!$entity) {
                return throw new NotFoundHttpException("Entity not found");
            }

            $boards = Entity::find()
                ->select(["id", "name", "uuid"])
                ->where([
                    "in", "id", UserEntity::find()->select(["entity_id"])
                        ->where([
                            "user_id" => Yii::$app->getUser()->getId(),
                        ])
                        ->AndWhere([
                            "entity_id" => $entity->id,
                        ])
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
        }

        return $this->render('index', [
            "entities" => $boards,
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
