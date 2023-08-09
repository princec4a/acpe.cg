<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_ville".
 *
 * @property int $id
 * @property string $nom
 * @property int $departement_id
 * @property int $pays_id
 *
 * @property KitEmploye[] $kitEmployes
 * @property KitEntreprise[] $kitEntreprises
 * @property KitDepartement $departement
 */
class KitVille extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_ville';
    }

    public function behaviors(){
        return [
            TimestampBehavior::class,
            [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false,
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nom', 'departement_id'], 'required'],
            [['departement_id', 'pays_id'], 'integer'],
            [['nom'], 'string', 'max' => 255],
            [['departement_id'], 'exist', 'skipOnError' => true, 'targetClass' => KitDepartement::className(), 'targetAttribute' => ['departement_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nom' => Yii::t('app', 'Nom'),
            'pays_id' => Yii::t('app', 'Pays ID'),
            'departement_id' => Yii::t('app', 'Departement ID'),
        ];
    }

    /**
     * Gets query for [[KitEmployes]].
     *
     * @return \yii\db\ActiveQuery|KitEmployeQuery
     */
    public function getKitEmployes()
    {
        return $this->hasMany(KitEmploye::className(), ['ville_travail' => 'id']);
    }

    /**
     * Gets query for [[KitEntreprises]].
     *
     * @return \yii\db\ActiveQuery|KitEntrepriseQuery
     */
    public function getKitEntreprises()
    {
        return $this->hasMany(KitEntreprise::className(), ['ville_id' => 'id']);
    }

    /**
     * Gets query for [[Departement]].
     *
     * @return \yii\db\ActiveQuery|KitDepartementQuery
     */
    public function getDepartement()
    {
        return $this->hasOne(KitDepartement::className(), ['id' => 'departement_id']);
    }

    /**
     * {@inheritdoc}
     * @return KitVilleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitVilleQuery(get_called_class());
    }
}
