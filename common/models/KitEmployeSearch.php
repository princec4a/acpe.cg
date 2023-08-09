<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * KitEmployeSearch represents the model behind the search form of `common\models\KitEmploye`.
 */
class KitEmployeSearch extends KitEmploye
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'nationalite', 'entreprise_id', 'ville_travail'], 'integer'],
            [['nom', 'nom_jeune_fille', 'prenom', 'date_naissance', 'lieu_naissance', 'email', 'sexe', 'lieu_travail', 'statut_medical', 'type_contrat', 'reference_contrat', 'fonction', 'date_embauche', 'date_fin_contrat', 'created_at', 'updated_at'], 'safe'],
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
        $enterprise = KitEntreprise::find()->where(['user_id' => \Yii::$app->user->id])->one();
        $query = KitEmploye::find();

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
            'date_naissance' => $this->date_naissance,
            'nationalite' => $this->nationalite,
            'entreprise_id' => $this->entreprise_id,
            'ville_travail' => $this->ville_travail,
            'date_embauche' => $this->date_embauche,
            'date_fin_contrat' => $this->date_fin_contrat,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'nom', $this->nom])
            ->andFilterWhere(['like', 'nom_jeune_fille', $this->nom_jeune_fille])
            ->andFilterWhere(['like', 'prenom', $this->prenom])
            ->andFilterWhere(['like', 'lieu_naissance', $this->lieu_naissance])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'sexe', $this->sexe])
            ->andFilterWhere(['like', 'lieu_travail', $this->lieu_travail])
            ->andFilterWhere(['like', 'statut_medical', $this->statut_medical])
            ->andFilterWhere(['like', 'type_contrat', $this->type_contrat])
            ->andFilterWhere(['like', 'reference_contrat', $this->reference_contrat])
            ->andFilterWhere(['like', 'fonction', $this->fonction]);

        if(!is_null($enterprise))
            $query->andWhere(['entreprise_id'=>$enterprise->id]);

        return $dataProvider;
    }
}
