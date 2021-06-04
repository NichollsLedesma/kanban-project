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
        // $entity_id = Yii::$app->request->post('entity_id');
        // $entity = Entity::find()
        //     ->where(["id" => $entity_id])
        //     ->where([
        //         "in", "id", UserEntity::find()->select(["entity_id"])
        //             ->where([
        //                 "user_id" => Yii::$app->getUser()->getId(),
        //                 "entity_id" => $entity_id,
        //             ]),
        //     ])
        //     ->limit(1)->one();
        $entity = Entity::findOne(Yii::$app->request->post('entity_id'));
        // VarDumper::dump( $entity);
        // die;

        if (!$entity) {
            return throw new UnprocessableEntityHttpException("entity not belong to the user.");
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $newBoard = new Board();
            $newBoard->title = Yii::$app->request->post('name');
            $newBoard->owner_id = Yii::$app->getUser()->getId();
            $newBoard->entity_id = $entity->id;
            $newBoard->save();
    
            $userBoard = new UserBoard();
            $userBoard->user_id = Yii::$app->getUser()->getId();
            $userBoard->board_id = $newBoard->id;
            $userBoard->save();
            Yii::$app->session->setFlash('success', "Collection created.");
            $transaction->commit();
        } catch (\Throwable $th) {
            Yii::$app->session->setFlash('error', "Error creating new collection.");
            $transaction->rollBack();
        }

        return $this->redirect(["kanban/board", "uuid" => $newBoard->uuid]);
    }

    public function getEntity()
    {
        $entities = Yii::$app->getUser()->getIdentity()->entities;

        return $entities[0];
    }

    public function actionUpdate($uuid)
    {
        $board = Board::find()->where(['uuid' => $uuid])->limit(1)->one();

        if (!$board) {
            return throw new NotFoundHttpException("board not found.");
        }

        try {
            $board->title = Yii::$app->request->post('title');
            $board->save();

            return true;
        } catch (Exception $e) {
            return throw new BadRequestHttpException("Error updating board: " . $e->getMessage());
        }
    }

    public function actionDelete($uuid)
    {
        $board = Board::find()->where(['uuid' => $uuid])->limit(1)->one();

        if (!$board) {
            return throw new NotFoundHttpException("board not found.");
        }

        $board->delete();

        return $this->redirect(["kanban/index"]);
    }
}
