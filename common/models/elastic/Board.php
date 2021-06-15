<?php

namespace common\models\elastic;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class Board extends \yii\elasticsearch\ActiveRecord
{
    public function attributes()
    {
        return ['title', "uuid", "owner_id", "entity_id"];
    }

    public function saving($data)
    {
        $properties = array_keys($data);

        foreach ($properties as $property) {
            $this->$property = $data[$property];
        }


        $this->insert();
    }


    public static function getFiltredCards($board_id, $search)
    {
        $cards = Card::find()->query([
            'bool' => [
                "must" => [
                    'wildcard' => ["title" =>"*$search*"],
                ],
                "filter" => [
                    "match" => ["board_id" => $board_id]
                ],
            ]
        ])
        ->source(["board_id", "title", "uuid","column_id"])
        ->asArray()
        ->all();

        return ArrayHelper::getColumn($cards, function ($card) {
            $source = $card["_source"];

            return [
                "id" => $source['uuid'],
                "value" => $source['title'],
                "label" => $source['title'],
            ];
        });
    }

    public function deleteDocument()
    {
        $this->delete([
            'index' => static::index(),
            'id'    => $this->_id,
        ]);

        return true;
    }

    public function updating($data)
    {
        $this->setAttributes([
            'title' => $data['title'],
        ], false);

        return true;
    }
}
