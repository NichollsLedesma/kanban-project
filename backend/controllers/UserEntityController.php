<?php

namespace backend\controllers;

use common\models\Entity;
use common\models\User;
use Yii;
use common\models\UserEntity;
use common\models\UserEntitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;

/**
 * UserEntityController implements the CRUD actions for UserEntity model.
 */
class UserEntityController extends Controller
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
     * Lists all UserEntity models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserEntitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserEntity model.
     * @param integer $user_id
     * @param integer $entity_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($user_id, $entity_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($user_id, $entity_id),
        ]);
    }

    /**
     * Creates a new UserEntity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserEntity();
        $users = \yii\helpers\ArrayHelper::map(User::find()->all(), 'id', 'username');
        $entities = \yii\helpers\ArrayHelper::map(Entity::find()->all(), 'id', 'name');
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if(Yii::$app->request->post("is_redirect_admin")=== "1"){
                return $this->redirect(["user/view", "id" => $model->user_id]);
            }
            return $this->redirect(['view', 'user_id' => $model->user_id, 'entity_id' => $model->entity_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'users' => $users,
            'entities' => $entities,
        ]);
    }

    /**
     * Updates an existing UserEntity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $user_id
     * @param integer $entity_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($user_id, $entity_id)
    {
        $model = $this->findModel($user_id, $entity_id);
        $users = \yii\helpers\ArrayHelper::map(User::find()->all(), 'id', 'username');
        $entities = \yii\helpers\ArrayHelper::map(Entity::find()->all(), 'id', 'name');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'user_id' => $model->user_id, 'entity_id' => $model->entity_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'users' => $users,
            'entities' => $entities,
        ]);
    }

    /**
     * Deletes an existing UserEntity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $user_id
     * @param integer $entity_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($user_id, $entity_id)
    {
        $this->findModel($user_id, $entity_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the UserEntity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $user_id
     * @param integer $entity_id
     * @return UserEntity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($user_id, $entity_id)
    {
        if (($model = UserEntity::findOne(['user_id' => $user_id, 'entity_id' => $entity_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
