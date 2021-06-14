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
        $entity = Entity::findOne(Yii::$app->request->post('entity_id'));

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

    public function actionLeave($uuid)
    {
        if (!$this->removeUser($uuid, Yii::$app->getUser()->getId())) {
            return throw new NotFoundHttpException("Impossible to remove user from board.");
        }

        return $this->redirect(["kanban/index"]);
    }

    public function actionRemoveUser($uuid, $user_id)
    {
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;

        if (!$this->removeUser($uuid, $user_id)) {
            return throw new NotFoundHttpException("Impossible to remove user from board.");
        }

        $response->data = ['message' => 'User removed from the board'];
    }

    private function removeUser($uuid, $user_id)
    {
        $board = Board::find()
            ->where(["uuid" => $uuid])
            ->limit(1)->one();

        if (!$board) {
            return throw new NotFoundHttpException("Board not found");
        }

        $userBoard = UserBoard::find()->where([
            "user_id" => $user_id,
            "board_id" => $board->id,
        ])->limit(1)->one();

        if (!$userBoard) {
            return throw new NotFoundHttpException("Relationship user-board not found");
        }

        return $userBoard->delete();
    }
}
