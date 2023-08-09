<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_historique_trajet".
 *
 * @property int $id
 * @property int $demande_id
 * @property int $user_id
 * @property int $date_reception
 * @property int $date_transmission
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property KitDemande $demande
 */
class KitHistoriqueTrajet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_historique_trajet';
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
            [['demande_id', 'user_id', 'date_reception', 'date_reception'], 'required'],
            [['demande_id', 'user_id', 'date_reception', 'date_reception', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['demande_id'], 'exist', 'skipOnError' => true, 'targetClass' => KitDemande::className(), 'targetAttribute' => ['demande_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'demande_id' => Yii::t('app', 'Demande ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'date_reception' => Yii::t('app', 'Date Reception'),
            'date_reception' => Yii::t('app', 'Dae Transmission'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * Gets query for [[Demande]].
     *
     * @return \yii\db\ActiveQuery|KitDemandeQuery
     */
    public function getDemande()
    {
        return $this->hasOne(KitDemande::className(), ['id' => 'demande_id']);
    }

    /**
     * {@inheritdoc}
     * @return KitHistoriqueTrajetQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitHistoriqueTrajetQuery(get_called_class());
    }
}
