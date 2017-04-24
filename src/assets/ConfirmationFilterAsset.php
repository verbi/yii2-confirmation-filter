<?php
namespace verbi\yii2ConfirmationFilter\behaviors;
use yii\web\AssetBundle;

class ConfirmationFilterAsset extends AssetBundle {
    public $depends = [
        'yii\web\YiiAsset',
    ];
    public $sourcePath = '@vendor/verbi/yii2-confirmation-filter/src/assets/assets';

    public $js = [
        'js/confirmation-filter.js',
    ];
}