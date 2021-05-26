<?php

namespace common\models;

/**
 * Description of elementCardForm
 *
 * @author Alejandro Zanlongo <azanlongo at gmail.com>
 */
class elementCardForm extends \yii\base\Model
{

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['title', 'description'], 'required'],
//            ['title', 'validateTitle'],
        ];
    }

}
