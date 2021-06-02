<?php

namespace backend\controllers;

use common\models\Board;
use common\models\User;
use Yii;
use common\models\UserBoard;
use common\models\UserBoardSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserBoardController implements the CRUD actions for UserBoard model.
 */
class UserBoardController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all UserBoard models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserBoardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserBoard model.
     * @param integer $user_id
     * @param integer $board_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($user_id, $board_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($user_id, $board_id),
        ]);
    }

    /**
     * Creates a new UserBoard model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserBoard();
        $users = \yii\helpers\ArrayHelper::map(User::find()->all(), 'id', 'username');
        $boards = \yii\helpers\ArrayHelper::map(Board::find()->all(), 'id', 'title');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'user_id' => $model->user_id, 'board_id' => $model->board_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'users' => $users,
            'boards' => $boards
        ]);
    }

    /**
     * Updates an existing UserBoard model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $user_id
     * @param integer $board_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($user_id, $board_id)
    {
        $model = $this->findModel($user_id, $board_id);
        $users = \yii\helpers\ArrayHelper::map(User::find()->all(), 'id', 'username');
        $boards = \yii\helpers\ArrayHelper::map(Board::find()->all(), 'id', 'title');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'user_id' => $model->user_id, 'board_id' => $model->board_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'users' => $users,
            'boards' => $boards
        ]);
    }

    /**
     * Deletes an existing UserBoard model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $user_id
     * @param integer $board_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($user_id, $board_id)
    {
        $this->findModel($user_id, $board_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the UserBoard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $user_id
     * @param integer $board_id
     * @return UserBoard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($user_id, $board_id)
    {
        if (($model = UserBoard::findOne(['user_id' => $user_id, 'board_id' => $board_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
