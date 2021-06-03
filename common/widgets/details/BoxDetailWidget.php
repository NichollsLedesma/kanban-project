<?php

namespace common\widgets\details;

use yii\base\Widget;

class BoxDetailWidget extends Widget
{
    public $user_id = null;
    public $items = [];
    public $title = "";
    public $key_class = "";
    public $class_relation = "";
    public $is_enable_to_create = true;
    public $to_load = [];

    public function run(): string
    {
        BoxDetailWidgetAsset::register($this->getView());

        return $this->render(
            '_boxDetail',
            [
                'user_id' => $this->user_id,
                'items' => $this->items,
                'title' => $this->title,
                'key_class' => $this->key_class,
                'class_relation' => $this->class_relation,
                'is_enable_to_create' => $this->is_enable_to_create,
                'to_load' => $this->to_load,
            ]
        );
    }
}
