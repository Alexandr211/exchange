<?php

namespace common\models\search;

use common\models\UserNew;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Orders;

/**
 * OrdersSearch represents the model behind the search form of `common\models\Orders`.
 */
class OrdersSearch extends Orders
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sum'], 'integer'],
            [['wallet_id', 'status', 'code', 'cr_time', 'comments', 'user_id'], 'safe'],
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
        $user_id = \Yii::$app->user->identity->id;
        $user_login = UserNew::findOne($user_id);
        $user_login = $user_login->login;
        $query = Orders::find();

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
            'sum' => $this->sum,
            'cr_time' => $this->cr_time,
        ]);

        $query->andFilterWhere(['like', 'wallet_id', $this->wallet_id])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'user_id', $user_login])
            ->andFilterWhere(['like', 'comments', $this->comments])
            ->orderBy('id DESC');

        return $dataProvider;
    }
}
