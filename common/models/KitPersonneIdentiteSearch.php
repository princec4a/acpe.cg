<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * KitPersonneIdentiteSearch represents the model behind the search form of `common\models\KitPersonneIdentite`.
 */
class KitPersonneIdentiteSearch extends KitPersonneIdentite
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'personne_id', 'type_piece_id'], 'integer'],
            [['numero', 'date_emission', 'date_expiration', 'created_at', 'updated_at'], 'safe'],
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
        $query = KitPersonneIdentite::find();

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
            'personne_id' => $this->personne_id,
            'type_piece_id' => $this->type_piece_id,
            'date_emission' => $this->date_emission,
            'date_expiration' => $this->date_expiration,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'numero', $this->numero]);

        return $dataProvider;
    }
}
