<?php

namespace common\models;

use yii\elasticsearch\ActiveQuery;

class ColumnQuery extends ActiveQuery
{
    public static function name($title)
    {
        return ['match' => ['title' => $title]];
    }

    // public static function address($address)
    // {
    //     return ['match' => ['address' => $address]];
    // }

    // public static function registrationDateRange($dateFrom, $dateTo)
    // {
    //     return ['range' => ['registered_at' => [
    //         'gte' => $dateFrom,
    //         'lte' => $dateTo,
    //     ]]];
    // }
}