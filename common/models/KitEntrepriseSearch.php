<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * KitEntrepriseSearch represents the model behind the search form of `common\models\KitEntreprise`.
 */
class KitEntrepriseSearch extends KitEntreprise
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ville_id'], 'integer'],
            [['raison_sociale', 'email', 'adresse_congo', 'logo', 'created_at', 'updated_at', 'sigle'], 'safe'],
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
        $query = KitEntreprise::find();

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
            'ville_id' => $this->ville_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'raison_sociale', $this->raison_sociale])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'adresse_congo', $this->adresse_congo])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'sigle', $this->sigle]);

        return $dataProvider;
    }
}
