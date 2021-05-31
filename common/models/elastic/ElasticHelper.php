<?php

namespace common\models\elastic;

use yii\helpers\VarDumper;

class ElasticHelper
{
    public static function create($class, $data)
    {
        $instance = new $class;
        $instance->saving($data);
    }

    public static function search($model, $match)
    {
        return $model::find()->query(['match' => $match])->one();
    }

    public static function getAll($model)
    {
        return $model::find()->all();
    }

    public static function remove($class, $match)
    {
        $doc = self::search($class, $match);

        if(!$doc){
            return true;
        }

        $doc->deleteDocument();
    }


}
