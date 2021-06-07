<?php

namespace common\widgets\listBoard;

use yii\base\Widget;
use common\widgets\listBoard\ListBoardWidgetAsset;

class ListBoardWidget extends Widget
{

    public $boards = [];
    public $entity_id = null;
    public $isEnableToCreate = false;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        ListBoardWidgetAsset::register($this->getView());

        return $this->render(
            '_board',
            [
                'boards' => $this->boards,
                'entity_id' => $this->entity_id,
                'isEnableToCreate' => $this->isEnableToCreate,
            ]
        );
    }
}
