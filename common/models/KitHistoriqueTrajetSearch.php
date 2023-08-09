<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\KitHistoriqueTrajet;

/**
 * KitHistoriqueTrajetSearch represents the model behind the search form of `common\models\KitHistoriqueTrajet`.
 */
class KitHistoriqueTrajetSearch extends KitHistoriqueTrajet
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'demande_id', 'user_id', 'date_reception', 'dae_transmission', 'created_at', 'updated_at', 'created_by'], 'integer'],
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
        $query = KitHistoriqueTrajet::find();

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
            'demande_id' => $this->demande_id,
            'user_id' => $this->user_id,
            'date_reception' => $this->date_reception,
            'dae_transmission' => $this->dae_transmission,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
        ]);

        return $dataProvider;
    }
}
