<?php

namespace common\models\elastic;

use Yii;
use yii\helpers\VarDumper;

class Board extends \yii\elasticsearch\ActiveRecord
{
    public function attributes()
    {
        return ['title', "uuid", "owner_id", "entity_id"];
    }

    public function saving($data)
    {
        $this->title = $data['title'];
        $this->uuid = $data['uuid'];
        $this->owner_id = $data['owner_id'];
        $this->entity_id = $data['entity_id'];

        return $this->insert();
    }

    public function searchingAllMatches($value)
    {
        return $this::find()->query([
            'bool' => [
                'must' => [
                    BoardQuery::title($value)
                ],
            ],
        ])->all();
    }

    public function deleteDocument()
    {
        $this->delete([
            'index' => static::index(),
            'id'    => $this->_id,
        ]);
    }
}
