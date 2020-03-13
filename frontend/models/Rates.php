<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "rates".
 *
 * @property int $id
 * @property string $cripto_name
 * @property int $curr_id
 * @property float $curr_price_buy
 * @property float $curr_price_sell
 * @property string $cr_time
 */
class Rates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cripto_name', 'curr_id', 'curr_price_buy', 'curr_price_sell'], 'required'],
            [['curr_id'], 'integer'],
            [['curr_price_buy', 'curr_price_sell'], 'number'],
            [['cr_time'], 'safe'],
            [['cripto_name'], 'string', 'max' => 255],
            [['cripto_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cripto_name' => 'Валютная пара',
            'curr_id' => 'Curr ID',
            'curr_price_buy' => 'Curr Price Buy',
            'curr_price_sell' => 'Текущий курс',
            'cr_time' => 'Дата',
        ];
    }
}
