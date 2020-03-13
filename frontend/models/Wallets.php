<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "wallets".
 *
 * @property int $id
 * @property string $user_id
 * @property string $wallet_id
 * @property string $comments
 * @property string $cr_time
 */
class Wallets extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wallets';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'string', 'max' => 255],
            [['wallet_id', 'comments'], 'required'],
            [['cr_time'], 'safe'],
            [['wallet_id'], 'string', 'max' => 255],
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
            'comments' => 'Комментарии',
            'cr_time' => 'Дата',
        ];
    }
}
