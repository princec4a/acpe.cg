<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * KitDemandeSearch represents the model behind the search form of `common\models\KitDemande`.
 */
class KitDemandeSearch extends KitDemande
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type_autorisation_id'], 'integer'],
            [['code'], 'string'],
            [['date_reception', 'statut', 'created_at', 'updated_at'], 'safe'],
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
        $query = KitDemande::find();

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
            'date_reception' => $this->date_reception,
            'type_autorisation_id' => $this->type_autorisation_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'statut', $this->statut])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'employe', $this->employe]);

        if(!is_null($enterprise)){
            $list_employe_id =  array_filter(ArrayHelper::map($enterprise->kitEmployes, 'id', 'id'));
            $query->andWhere(['employe_id'=>$list_employe_id]);
        }


        return $dataProvider;
    }
}
