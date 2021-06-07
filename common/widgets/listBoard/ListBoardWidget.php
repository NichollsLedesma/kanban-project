<?php

namespace common\widgets\listBoard;

use yii\base\Widget;
use common\widgets\listBoard\ListBoardWidgetAsset;

class ListBoardWidget extends Widget
{

    public $boards = [];
    public $entities = [];
    public $title = '';
    public $isEnableToCreate = false;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        // if (count($this->boards) === 0) {
        //     return "<h1>Nothing to show</h1>";
        // }

        ListBoardWidgetAsset::register($this->getView());

        return $this->render(
            '_board',
            [
                'boards' => $this->boards,
                'entities' => $this->entities,
                'title' => $this->title,
                'isEnableToCreate' => $this->isEnableToCreate,
            ]
        );
    }
}
