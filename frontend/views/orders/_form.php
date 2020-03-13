<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Wallets;

/* @var $this yii\web\View */
/* @var $model common\models\Orders */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-form">
    <?php
        if(!Yii::$app->user->isGuest){
            $user_id = Yii::$app->user->identity->login;
        }
    ?>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'wallet_id')->dropdownList(
        Wallets::find()->select(['wallet_id'])->where(['user_id'=>$user_id])->indexBy('wallet_id')->column(),
        ['prompt'=>'Выберите кошелек']
    ) ?>

    <?= $form->field($model, 'sum')->textInput() ?>

    <?// $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <?// $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comments')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
