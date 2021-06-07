<?php

namespace frontend\models;

/**
 * Description of DeleteCardForm
 *
 * @author Alejandro Zanlongo <azanlongo at gmail.com>
 */
class DeleteCardForm extends \yii\base\Model
{

    public $cardId;

    public function rules()
    {

        return [
            [['cardId'], 'required'],
        ];
    }


}
