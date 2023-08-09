<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_level".
 *
 * @property int $id
 * @property string $level_name
 * @property string|null $level_description
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $level_number
 * @property int $status_code
 * @property string $status_name
 * @property string $status_colore
 *
 * @property KitCircuitValidation[] $kitCircuitValidations
 */
class KitLevel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_level';
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
            [['level_name', 'level_number', 'level_description','status_code', 'status_name'], 'required'],
            [['created_at', 'updated_at', 'created_by', 'level_number', 'status_code'], 'integer'],
            ['level_number', 'unique'],
            [['level_name', 'status_code'], 'string', 'max' => 255],
            [['status_colore'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'level_name' => Yii::t('app', 'Level Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'level_number' => Yii::t('app', 'Level Number'),
            'level_description' => Yii::t('app', 'Level Description'),
            'status_code' => Yii::t('app', 'Status Code'),
            'status_name' => Yii::t('app', 'Status Name'),
            'status_colore' => Yii::t('app', 'Status Colore'),
        ];
    }

    /**
     * Gets query for [[KitCircuitValidations]].
     *
     * @return \yii\db\ActiveQuery|KitCircuitValidationQuery
     */
    public function getKitCircuitValidations()
    {
        return $this->hasMany(KitCircuitValidation::className(), ['level_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return KitLevelQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitLevelQuery(get_called_class());
    }

    public static function getMaxLevel(){
        return KitLevel::find()->max('status_code');
    }
}
