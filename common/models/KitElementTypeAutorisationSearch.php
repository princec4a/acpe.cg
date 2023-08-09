<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * KitElementTypeAutorisationSearch represents the model behind the search form of `common\models\KitElementTypeAutorisation`.
 */
class KitElementTypeAutorisationSearch extends KitElementTypeAutorisation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'piece_fournir_id', 'type_autorisation_id', 'nombre', 'a_joindre', 'obligatoire'], 'integer'],
            [['created_at', 'updated_at', 'date_effective', 'date_fin'], 'safe'],
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
        $query = KitElementTypeAutorisation::find();

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
            'type_autorisation_id' => $this->type_autorisation_id,
            'nombre' => $this->nombre,
            'a_joindre' => $this->a_joindre,
            'obligatoire' => $this->obligatoire,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'date_effective' => $this->date_effective,
            'date_fin' => $this->date_fin,
        ]);

        return $dataProvider;
    }
}
