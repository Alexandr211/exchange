<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\search\WalletsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мои кошельки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wallets-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Указать новый кошелек', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
        //    'id',
        //    'user_id',
            'wallet_id',
            'comments',
            'cr_time',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
