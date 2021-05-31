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
        $properties = array_keys($data);

        foreach ($properties as $property) {
            $this->$property = $data[$property];
        }


        $this->insert();
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
