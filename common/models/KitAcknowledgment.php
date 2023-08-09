<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_acknowledgment".
 *
 * @property int $id
 * @property string $code
 * @property int $demande_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property KitDemande $demande
 * @property KitElementDossierPhysique[] $kitElementDossierPhysiques
 */
class KitAcknowledgment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_acknowledgment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['demande_id', 'code'], 'required'],
            [['code'], 'unique'],
            [['demande_id', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['demande_id'], 'exist', 'skipOnError' => true, 'targetClass' => KitDemande::className(), 'targetAttribute' => ['demande_id' => 'id']],
        ];
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
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'demande_id' => Yii::t('app', 'N° Demande'),
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
     * Gets query for [[KitElementDossierPhysiques]].
     *
     * @return \yii\db\ActiveQuery|KitElementDossierPhysiqueQuery
     */
    public function getKitElementDossierPhysiques()
    {
        return $this->hasMany(KitElementDossierPhysique::className(), ['acknowledgment_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return KitAcknowledgmentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitAcknowledgmentQuery(get_called_class());
    }
}
