<?php

namespace frontend\controllers;

use common\models\Board;
use common\models\Entity;
use common\models\UserBoard;
use common\models\UserEntity;
use Exception;
use Yii;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

class BoardController extends Controller
{
    public function behaviors()
    {
        return [];
    }

    public function actionCreate()
    {
        $entity_id = Yii::$app->request->post('entity_id');
        $entity = Entity::find()
            ->where(["id" => $entity_id])
            ->where([
                "in", "id", UserEntity::find()->select(["entity_id"])
                    ->where([
                        "user_id" => Yii::$app->getUser()->getId(),
                        "entity_id" => $entity_id,
                    ]),
            ])
            ->limit(1)->one();

        if (!$entity) {
            return throw new UnprocessableEntityHttpException("entity not belong to the user.");
        }

        $newBoard = new Board();
        $newBoard->title = Yii::$app->request->post('name');
        $newBoard->owner_id = Yii::$app->getUser()->getId();
        $newBoard->entity_id = $entity->id;
        $newBoard->save();

        $userBoard = new UserBoard();
        $userBoard->user_id = Yii::$app->getUser()->getId();
        $userBoard->board_id = $newBoard->id;
        $userBoard->save();

        return $this->redirect(["kanban/board", "uuid" => $newBoard->uuid]);
    }

    public function getEntity()
    {
        $entities = Yii::$app->getUser()->getIdentity()->entities;

        return $entities[0];
    }

    public function actionUpdate()
    {
        $boardUuid = Yii::$app->request->get('uuid');
        $board = Board::find()->where(['uuid' => $boardUuid])->limit(1)->one();

        if (!$board) {
            return throw new NotFoundHttpException("board not found.");
        }

        try {
            $board->name = Yii::$app->request->post('name');
            $board->save();

            return $this->redirect(["kanban/board", "uuid" => $board->uuid]);
        } catch (Exception $e) {
            return throw new BadRequestHttpException("Error updating board: " . $e->getMessage());
        }
    }

    public function actionDelete()
    {
        $boardUuid = Yii::$app->request->get('uuid');
        $board = Board::find()->where(['uuid' => $boardUuid])->limit(1)->one();

        if (!$board) {
            return throw new NotFoundHttpException("board not found.");
        }

        $board->delete();
    }
}
