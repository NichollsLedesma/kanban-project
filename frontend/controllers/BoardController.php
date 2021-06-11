<?php

namespace frontend\controllers;

use common\models\Board;
use common\models\BoardRepository;
use common\models\Card;
use common\models\CardRepository;
use common\models\Column;
use common\models\CreateColumnForm;
use common\models\Entity;
use common\models\UpdateColumnForm;
use common\models\UserBoard;
use common\models\UserEntity;
use common\widgets\BoardCard\BoardCard;
use Exception;
use frontend\models\CreateCardForm;
use Yii;
use yii\helpers\Url;
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

    public function actionArchiveColumn($uuid)
    {
        $column = Column::find()->where(['uuid' => $uuid])->limit(1)->one();

        if (!$column) {
            return false;
        }

        $topic = Url::to(['/board/index', 'uuid' => $column->board->uuid]);
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

            Yii::$app->mqtt->sendMessage(Url::to(['board/index', 'uuid' => $boardUuid]), $obj);

            Yii::$app->session->setFlash('deleted', true);
        }
        if ($this->request->isPost && !$this->request->post('DeleteCardForm') && $userCardModel->load($this->request->post()) && $userCardModel->validate() && $userCardModel->save()) {
            $obj = ['type' => 'card', 'action' => 'update', 'params' => ['cardId' => $userCardModel->uuid, 'title' => $userCardModel->title, 'description' => $userCardModel->description, 'color' => $userCardModel->color]];

            Yii::$app->mqtt->sendMessage(Url::to(['board/index', 'uuid' => $boardUuid]), $obj);
            Yii::$app->session->setFlash('updated', true);
        }
        return $this->renderAjax('_cardUpdate', ['model' => $userCardModel, 'deleteModel' => $deleteCardModel]);
    }

    public function actionIndex($uuid)
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
            Yii::$app->mqtt->sendMessage(Url::to(['board/index', 'uuid' => $uuid]), $obj);
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
                Yii::$app->mqtt->sendMessage(Url::to(['board/index', 'uuid' => $uuid]), $obj);
                $this->response->headers->set('X-PJAX-URL', Url::to(['/board/index', 'uuid' => $uuid]));
                unset($newCardModel);
            }
        }

        if ($this->request->isPjax && $this->request->get('addColumn')) {

            $newColumnModel = new CreateColumnForm(['scenario' => Column::SCENARIO_AJAX_CREATE]);
            $newColumnModel->board_id = $userBoard->select(['id'])->limit(1)->one()->id;
            if ($this->request->isPost && $newColumnModel->load($this->request->post()) && $newColumnModel->validate() && $newColumnModel->createColumn()) {
                $newColumnModel->columnCreated(Url::to(['board/index', 'uuid' => $uuid]));
                $this->response->headers->set('X-PJAX-URL', Url::to(['/board/index', 'uuid' => $uuid]));
                unset($newColumnModel);
            }
        }

        if ($this->request->isPjax && $this->request->get('updateColumn')) {
            $uuidColumn = $this->request->get('updateColumn');
            $updateColumnModel = new UpdateColumnForm(['scenario' => Column::SCENARIO_AJAX_UPDATE]);
            $updateColumnModel = $updateColumnModel->find()->where(['uuid' => $uuidColumn])->one();
            if ($this->request->isPost && $updateColumnModel->load($this->request->post()) && $updateColumnModel->validate()) {
                $updateColumnModel->save();
                $updateColumnModel->columnUpdated(Url::to(['board/index', 'uuid' => $uuid]));
                $this->response->headers->set('X-PJAX-URL', Url::to(['/board/index', 'uuid' => $uuid]));
                unset($updateColumnModel);
            }
        }

        $board = Board::find()->where(["uuid" => $uuid])->limit(1)->one();

        return $this->render('index', [
            'boardName' => $board->title,
            'members' => $board->users,
            'boardUuid' => $uuid,
            'boardColumns' => $boardColumns,
            'newCardModel' => $newCardModel ?? null,
            'newColumnModel' => $newColumnModel ?? null,
            'updateColumnModel' => $updateColumnModel ?? null,
        ]);
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

        return $this->redirect(["board/index", "uuid" => $newBoard->uuid]);
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
        $board = Board::find()
            ->where(["uuid" => $uuid])
            ->limit(1)->one();

        if (!$board) {
            return throw new NotFoundHttpException("Board not found");
        }

        $userBoard = UserBoard::find()->where([
            "user_id" => Yii::$app->getUser()->getId(),
            "board_id" => $board->id,
        ])->limit(1)->one();

        if (!$userBoard) {
            return throw new NotFoundHttpException("Relationshio user-board not found");
        }

        $userBoard->delete();

        return $this->redirect(["kanban/index"]);
    }
}
