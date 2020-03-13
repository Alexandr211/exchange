<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Wallets */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wallets-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'wallet_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comments')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
