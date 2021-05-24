<?php

namespace common\widgets\listBoard;

use yii\web\AssetBundle;

class ListBoardWidgetAsset extends AssetBundle
{
    public $sourcePath = '@common/widgets/listBoard/assets';

    public $js = [];

    public $css = [
        'css/main.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];

    public $publishOptions = [
        'forceCopy' => true,
    ];
}
