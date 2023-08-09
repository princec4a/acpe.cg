<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * KitElementDemandeSearch represents the model behind the search form of `common\models\KitElementDemande`.
 */
class KitElementDemandeSearch extends KitElementDemande
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'piece_fournir_id', 'demande_id'], 'integer'],
            [['fichier', 'created_at', 'updated_at'], 'safe'],
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
        $query = KitElementDemande::find();

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
            'piece_fournir_id' => $this->piece_fournir_id,
            'demande_id' => $this->demande_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'fichier', $this->fichier]);

        return $dataProvider;
    }
}
