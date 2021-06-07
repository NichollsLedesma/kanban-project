<?php

namespace common\widgets\details;

class BoxDetailWidgetAsset extends \yii\web\AssetBundle
{

    public $sourcePath = '@common/widgets/details/assets';
    public $js = [
        'js/main.js',
    ];
    public $css = [
        'css/main.css',
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
    public $publishOptions = [
        'forceCopy' => false,
    ];
}
