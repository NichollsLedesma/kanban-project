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
        $properties = array_keys($data);

        foreach ($properties as $property) {
            $this->$property = $data[$property];
        }

        return $this->insert();
    }

    public function deleteDocument()
    {
        $this->delete([
            'index' => static::index(),
            'id'    => $this->_id,
        ]);
    }
}
