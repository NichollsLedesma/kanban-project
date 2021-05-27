<?php

namespace common\models\elastic;

use Yii;
use yii\helpers\VarDumper;

class Card extends \yii\elasticsearch\ActiveRecord
{
    public function attributes()
    {
    return ['title', "uuid", "owner_id", "column_id", "description", "order", "color"];
    }

    public function saving($data)
    {
        $this->title = $data['title'];
        $this->uuid = $data['uuid'];
        $this->owner_id = $data['owner_id'];
        $this->column_id = $data['column_id'];
        $this->description = $data['description'];
        $this->color = $data['color'];
        $this->order = $data['order'];

        return $this->insert();
    }

    public function searchingAllMatches($value)
    {
        return $this::find()->query([
            'bool' => [
                'must' => [
                    CardQuery::title($value)
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
