<?php

namespace frontend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Wallets;

/**
 * WalletsSearch represents the model behind the search form of `frontend\models\Wallets`.
 */
class WalletsSearch extends Wallets
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['wallet_id', 'cr_time', 'comments'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $user_id = \Yii::$app->user->identity->login;
    //   $query = Wallets::find()->where(['user_id'=>$user_id])->all();
        $query = Wallets::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $user_id,
            'cr_time' => $this->cr_time,
            'comments' => $this->comments,
        ]);

        $query->andFilterWhere(['like', 'wallet_id', $this->wallet_id]);

        return $dataProvider;
    }
}
