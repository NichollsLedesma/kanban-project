<?php

namespace frontend\controllers;

use common\models\Entity;
use common\models\UserEntity;
use Yii;
use yii\helpers\VarDumper;
use yii\web\Controller;

class TestController extends Controller
{
    public function behaviors()
    {
        return [];
    }

    public function actionUserTestConfig()
    {
        $entityTest = new Entity();
        $entityTest->name = 'name entity test';
        $entityTest->owner_id = Yii::$app->getUser()->getId();
        $entityTest->save();

        $userEntityTest = new UserEntity();
        $userEntityTest->user_id = Yii::$app->getUser()->getId();
        $userEntityTest->entity_id = $entityTest->id;
        $userEntityTest->save();
        
        $this->printTest($userEntityTest);
        // return $this->redirect(["kanban"]);
    }

    private function printTest($data)
    {
        echo "<pre>";
        VarDumper::dump($data);
        echo "</pre>";
        die;
    }
}
