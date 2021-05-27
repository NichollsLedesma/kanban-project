<?php
 
namespace common\models\elastic;

use yii\elasticsearch\ActiveDataProvider;
use yii\elasticsearch\ActiveQuery;
use yii\elasticsearch\Query;
use yii\elasticsearch\QueryBuilder;

class BoardQuery extends Board
{
    public static function title($title)
    {
        return ['match' => ['title' => $title]];
    }
}