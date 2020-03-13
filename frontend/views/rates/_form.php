<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Rates */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rates-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cripto_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'curr_id')->textInput() ?>

    <?= $form->field($model, 'curr_price_buy')->textInput() ?>

    <?= $form->field($model, 'curr_price_sell')->textInput() ?>

    <?= $form->field($model, 'cr_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
