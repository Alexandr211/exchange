<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupNew */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Регистрация';
$title_after = 'Регистрационные данные';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if($model->attr == 'hidden') { ?>
<div class="site-signup hidden">
<?php }else{ ?>
<div class="site-signup">
<?php }?>
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Пожалуйста, пройдите проверку, что Вы не робот и далее - нажмите кнопку "Зарегистрироваться"!</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup1']); ?>
            <?= $form->field($model, 'reCaptcha')->widget(
                \himiklab\yii2\recaptcha\ReCaptcha2::className()) ?>
            <?= $form->field($model, 'count')->hiddenInput(['value'=>1])->label(false); ?>
            <div class="form-group">
                <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary', 'name' => 'signup1-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php if($model->attr == 'hidden') { ?>
<div class="site-signup ">
<?php }else{ ?>
<div class="site-signup hidden">
<?php }?>
    <h1><?= Html::encode($title_after) ?></h1>
    <p>Ваши логин и пароль:</p>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <?= $form->field($model, 'login')->textInput(['maxlength'=>true, 'readonly' => true]); ?>
            <?= $form->field($model, 'pass')->textInput(['maxlength'=>true, 'readonly' => true]); ?>
            <div class="form-group">
                <div class="form-group">
                    <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>