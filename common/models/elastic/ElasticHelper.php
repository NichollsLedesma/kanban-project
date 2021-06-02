<?php

namespace common\models\elastic;

use yii\helpers\VarDumper;

class ElasticHelper
{
    public static function create($class, $data)
    {
        $instance = new $class;
        $properties = array_keys($data);

        foreach ($properties as $property) {
            $instance->$property = $data[$property];
        }

        return $instance->insert();
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
