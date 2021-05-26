<?php

namespace common\widgets\listBoard;

use yii\base\Widget;

class CardWidget extends Widget
{
    public $board;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('_card', ['board' => $this->board]);
    }
}
