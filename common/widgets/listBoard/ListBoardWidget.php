<?php

namespace common\widgets\listBoard;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use common\widgets\listBoard\ListBoardWidgetAsset;

class ListBoardWidget extends Widget
{

    public $boards = [];
    public $title = '';

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        if (count($this->boards) === 0) {
            return "<h1>Nothing to show</h1>";
        }

        ListBoardWidgetAsset::register($this->getView());

        return $this->render(
            '_board',
            [
                'boards' => $this->boards,
                'title' => $this->title,
            ]
        );
    }
}
