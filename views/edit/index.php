<?php
/* @var $this yii\web\View */
?>

<?php

use yii\helpers\Html;
use dmstr\jsoneditor\JsonEditorWidget;
use yii\widgets\ActiveForm;


$example_schema = "";

$form = ActiveForm::begin([
    'method' => 'post',
]);

echo Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-success' ]);

echo $form->field($model, 'JSON')->widget(JsonEditorWidget::className(), [
    'schema' => $example_schema,
    'clientOptions' => [
        'theme' => 'bootstrap4',
        'disable_collapse' => false,
        'disable_edit_json' => false,
        'disable_properties' => true,
        'no_additional_properties' => false,
    ],
]);

echo Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-success' ]);


ActiveForm::end();
?>