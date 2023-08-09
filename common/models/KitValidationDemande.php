<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_validation_demande".
 *
 * @property int $id
 * @property int $demande_id
 * @property string $observation
 * @property int $level
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property resource|null $file
 * @property string|null $file_name
 *
 * @property KitDemande $demande
 */
class KitValidationDemande extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_validation_demande';
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
            [['demande_id', 'observation'], 'required'],
            [['demande_id', 'level', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['observation', 'file'], 'string'],
            [['file_name'], 'string', 'max' => 255],
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
            'observation' => Yii::t('app', 'Observation'),
            'level' => Yii::t('app', 'Level'),
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
     * @return KitValidationDemandeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitValidationDemandeQuery(get_called_class());
    }
}
