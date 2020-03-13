<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="orders-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать заявку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
    //    'filterModel' => $searchModel,
        'columns' => [
        //    ['class' => 'yii\grid\SerialColumn'],
            'id',
            'wallet_id',
            'sum',
            'status',
            [
                'label' => 'Ссылка на оплату',
                'attribute' => 'code',
                'value' => function ($model) {
                    if ($model->code == 'in the process') {
                        return $model->code;
                    }
                    return Html::a($model->code, $model->code, ['target' => '_blank']);
                },
                'format' => 'raw',
            ],
            'cr_time',
            'comments',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);


    ?>

    <p>
        <?= Html::a('Reload', ['index'], ['class' => 'btn btn-success hidden', 'id' => 'reload_orders']) ?>
    </p>

    <?php Pjax::end(); ?>

</div>
