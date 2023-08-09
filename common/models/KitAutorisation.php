<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_autorisation".
 *
 * @property int $id
 * @property string $code
 * @property int $employe_id
 * @property int $demande_id
 * @property int $type_autorisation_id
 * @property string $signataire
 * @property string $statut
 * @property string $fichier
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property KitDemande $demande
 * @property KitEmploye $employe
 * @property KitTypeAutorisation $typeAutorisation
 */
class KitAutorisation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_autorisation';
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
            [['code', 'employe_id', 'demande_id', 'type_autorisation_id', 'signataire', 'statut', 'fichier'], 'required'],
            [['employe_id', 'demande_id', 'type_autorisation_id', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['statut', 'fichier'], 'string'],
            [['code', 'signataire'], 'string', 'max' => 255],
            [['code'], 'unique'],
            [['demande_id'], 'exist', 'skipOnError' => true, 'targetClass' => KitDemande::className(), 'targetAttribute' => ['demande_id' => 'id']],
            [['employe_id'], 'exist', 'skipOnError' => true, 'targetClass' => KitEmploye::className(), 'targetAttribute' => ['employe_id' => 'id']],
            [['type_autorisation_id'], 'exist', 'skipOnError' => true, 'targetClass' => KitTypeAutorisation::className(), 'targetAttribute' => ['type_autorisation_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'employe_id' => Yii::t('app', 'Employe ID'),
            'demande_id' => Yii::t('app', 'Demande ID'),
            'type_autorisation_id' => Yii::t('app', 'Type Autorisation ID'),
            'signataire' => Yii::t('app', 'Signataire'),
            'statut' => Yii::t('app', 'Statut'),
            'fichier' => Yii::t('app', 'Fichier'),
            'create_at' => Yii::t('app', 'Create At'),
            'update_at' => Yii::t('app', 'Update At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * Gets query for [[Demande]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDemande()
    {
        return $this->hasOne(KitDemande::className(), ['id' => 'demande_id']);
    }

    /**
     * Gets query for [[Employe]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmploye()
    {
        return $this->hasOne(KitEmploye::className(), ['id' => 'employe_id']);
    }

    /**
     * Gets query for [[TypeAutorisation]].
     *
     * @return \yii\db\ActiveQuery|KitTypeAutorisationQuery
     */
    public function getTypeAutorisation()
    {
        return $this->hasOne(KitTypeAutorisation::className(), ['id' => 'type_autorisation_id']);
    }

    /**
     * {@inheritdoc}
     * @return KitAutorisationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitAutorisationQuery(get_called_class());
    }
}
