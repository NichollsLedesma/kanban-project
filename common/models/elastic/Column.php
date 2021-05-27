<?php

namespace common\models\elastic;

use Yii;
use yii\helpers\VarDumper;

class Column extends \yii\elasticsearch\ActiveRecord
{
    public function attributes()
    {
        return ['title', "uuid", "owner_id", "board_id"];
    }

    public function saving($data)
    {
        $this->title = $data['title'];
        $this->uuid = $data['uuid'];
        $this->owner_id = $data['owner_id'];
        $this->board_id = $data['board_id'];

        return $this->insert();
    }

    public function searchingAllMatches($value)
    {
        return $this::find()->query([
            'bool' => [
                'must' => [
                    ColumnQuery::title($value)
                ],
            ],
        ])->all();
    }

    // /**
    //  * @return array This model's mapping
    //  */
    // public static function mapping()
    // {
    //     return [
    //         // Field types: https://www.elastic.co/guide/en/elasticsearch/reference/current/mapping.html#field-datatypes
    //         'properties' => [
    //             'uuid'          => ['type' => 'keyword'],
    //             'board_id'      => ['type' => 'integer'],
    //             'owner_id'      => ['type' => 'integer'],
    //             'title'         => ['type' => 'text'],
    //             'order'         => ['type' => 'integer'],
    //             'created_by'    => ['type' => 'integer'],
    //             'updated_by'    => ['type' => 'integer'],
    //             'created_at'    => ['type' => 'integer'],
    //             'updated_at'    => ['type' => 'integer'],
    //         ]
    //     ];
    // }


    // /**
    //  * Set (update) mappings for this model
    //  */
    // public static function updateMapping()
    // {
    //     $db = static::getDb();
    //     $command = $db->createCommand();
    //     $command->setMapping(static::index(), static::type(), static::mapping());
    // }

    // /**
    //  * Create this model's index
    //  */
    // public static function createIndex()
    // {
    //     $db = static::getDb();
    //     $command = $db->createCommand();
    //     $command->createIndex(static::index(), [
    //         //'aliases' => [ /* ... */ ],
    //         'mappings' => static::mapping(),
    //         //'settings' => [ /* ... */ ],
    //     ]);
    // }

    // /**
    //  * Delete this model's index
    //  */
    // public static function deleteIndex()
    // {
    //     $db = static::getDb();
    //     $command = $db->createCommand();
    //     $command->deleteIndex(static::index(), static::type());
    // }
}
