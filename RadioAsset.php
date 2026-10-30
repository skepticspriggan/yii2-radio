<?php
namespace SkepticSpriggan;

use yii\web\AssetBundle;

class RadioAsset extends AssetBundle
{
    public $sourcePath = '@vendor/skepticspriggan/yii2-radio/assets';

    public $css = [
        'radio.css',
    ];

    public $js = [
        'radio.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
