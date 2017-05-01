<?php
$this->title = Yii::t('app','Please Confirm');
use \Yii;
use \verbi\yii2Helpers\widgets\builder\ActiveForm;
use \verbi\yii2Helpers\Html;
ActiveForm::begin([
    'method' => $method,
]);
echo Html::hiddenInput($headerName, true);
echo Html::paragraph('Are you sure you want to perform this action?');
echo Html::a(Yii::t('app','Cancel'), $returnUrl, ['class' => 'btn']);
echo Html::submitButton(Yii::t('app','Confirm'), ['class' => 'btn btn-primary']);
ActiveForm::end();