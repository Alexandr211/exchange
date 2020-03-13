<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\search\RatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Курс';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rates-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <? // Html::a('Create Rates', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
    //    'filterModel' => $searchModel,
        'layout'=>"{items}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
        //    'id',
            'cripto_name',
        //    'curr_id',
        //    'curr_price_buy',
            'curr_price_sell',
            'cr_time',
         //   ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <p>
        <?= Html::a('Reload', ['index'], ['class' => 'btn btn-success hidden', 'id' => 'reload_rates']) ?>
    </p>

    <?php Pjax::end(); ?>



</div>
