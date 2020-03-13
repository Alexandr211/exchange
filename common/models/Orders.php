<?php

namespace common\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property string $user_id
 * @property string $wallet_id
 * @property int $exmo_id
 * @property int $exmo_out_id
 * @property float $sum
 * @property float $out_sum
 * @property string $status
 * @property string $code
 * @property string $cr_time
 * @property string $comments
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['wallet_id', 'sum', 'comments'], 'required'],
            [['sum', 'out_sum', 'exmo_id', 'exmo_out_id'], 'number'],
            [['cr_time'], 'safe'],
            [['wallet_id', 'status', 'code', 'user_id'], 'string', 'max' => 255],
            [['comments'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'wallet_id' => 'Wallet ID',
            'sum' => 'Сумма',
            'status' => 'Статус платежа',
            'code' => 'Ссылка на оплату',
            'cr_time' => 'Дата',
            'comments' => 'Комментарии',
        ];
    }
}
