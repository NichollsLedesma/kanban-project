<?php

namespace common\widgets\BoardCard;

/**
 * Description of BoardCardAsset
 *
 * @author Alejandro Zanlongo <azanlongo at gmail.com>
 */
class BoardCardAsset extends \yii\web\AssetBundle
{

    public $sourcePath = '@common/widgets/BoardCard/assets';
    public $js = [
        'js/board-card.js',
    ];
    public $css = [
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
    public $publishOptions = [
        'forceCopy' => false,
    ];

}
