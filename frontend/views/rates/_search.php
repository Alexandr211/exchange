<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\search\RatesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rates-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'cripto_name') ?>

    <?= $form->field($model, 'curr_id') ?>

    <?= $form->field($model, 'curr_price_buy') ?>

    <?= $form->field($model, 'curr_price_sell') ?>

    <?php // echo $form->field($model, 'cr_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
