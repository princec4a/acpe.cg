<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * KitTypeAutorisationSearch represents the model behind the search form of `backend\models\KitTypeAutorisation`.
 */
class KitTypeAutorisationSearch extends KitTypeAutorisation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'signataire'], 'integer'],
            [['code', 'libelle', 'type_duree', 'nationalite','created_at', 'updated_at'], 'safe'],
            [['duree_validite'], 'number'],
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
        $query = KitTypeAutorisation::find();

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
            'duree_validite' => $this->duree_validite,
            'signataire' => $this->signataire,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'libelle', $this->libelle])
            ->andFilterWhere(['like', 'type_duree', $this->type_duree])
            ->andFilterWhere(['like', 'nationalite', $this->nationalite]);

        return $dataProvider;
    }
}
