<?php
/* @var $this yii\web\View */
?>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'method' => 'post',
]);


echo $form->field($model, 'DOWNLOADURL');
echo $form->field($model, 'NEWFILENAME');

echo Html::submitButton(Yii::t('app', 'Download'), ['class' => 'btn btn-success' ]);


ActiveForm::end();
?>