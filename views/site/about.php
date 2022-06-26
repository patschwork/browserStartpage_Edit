<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

use yii\bootstrap4\Alert;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        GitHub to this tool: <a href="https://github.com/patschwork/browserStartpage_Edit">https://github.com/patschwork/browserStartpage_Edit</a>
    </p>
    <p>
        GitHub to browserStartpage: <a href="https://github.com/saschadiercks/browserStartpage">https://github.com/saschadiercks/browserStartpage</a>
    </p>

    <?php
        Alert::begin([
            'options' => [
                'class' => 'alert-info',
                
            ],
            'closeButton' => false,
        ]);

        echo 'The authors of the tools are different. The author of this edit tool only uses the great browserStartpage from Sascha Diercks :-)';

        Alert::end();
    ?>


</div>
