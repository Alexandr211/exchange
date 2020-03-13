<?php

namespace frontend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Rates;

/**
 * RatesSearch represents the model behind the search form of `frontend\models\Rates`.
 */
class RatesSearch extends Rates
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'curr_id', 'curr_price_buy', 'curr_price_sell'], 'integer'],
            [['cripto_name', 'cr_time'], 'safe'],
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
        $query = Rates::find();

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
            'curr_id' => $this->curr_id,
            'curr_price_buy' => $this->curr_price_buy,
            'curr_price_sell' => $this->curr_price_sell,
            'cr_time' => $this->cr_time,
        ]);

        $query->andFilterWhere(['like', 'cripto_name', $this->cripto_name]);

        return $dataProvider;
    }
}
