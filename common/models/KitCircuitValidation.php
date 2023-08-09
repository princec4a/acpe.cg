<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_circuit_validation".
 *
 * @property int $id
 * @property int $autorization_type_id
 * @property int $level_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property KitLevel $level
 * @property KitTypeAutorisation $autorizationType
 */
class KitCircuitValidation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_circuit_validation';
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
            [['level_id'], 'required'],
            [['autorization_type_id', 'level_id', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['level_id'], 'exist', 'skipOnError' => true, 'targetClass' => KitLevel::className(), 'targetAttribute' => ['level_id' => 'id']],
            [['autorization_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => KitTypeAutorisation::className(), 'targetAttribute' => ['autorization_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'autorization_type_id' => Yii::t('app', 'Autorization Type ID'),
            'level_id' => Yii::t('app', 'Level ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * Gets query for [[Level]].
     *
     * @return \yii\db\ActiveQuery|KitLevelQuery
     */
    public function getLevel()
    {
        return $this->hasOne(KitLevel::className(), ['id' => 'level_id']);
    }

    /**
     * Gets query for [[AutorizationType]].
     *
     * @return \yii\db\ActiveQuery|KitTypeAutorisationQuery
     */
    public function getAutorizationType()
    {
        return $this->hasOne(KitTypeAutorisation::className(), ['id' => 'autorization_type_id']);
    }

    /**
     * {@inheritdoc}
     * @return KitCircuitValidationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitCircuitValidationQuery(get_called_class());
    }

    public static function getMaxLevel($authorisationType){
        $id = KitCircuitValidation::find()->where(['autorization_type_id'=>$authorisationType])->max('id');
        return KitCircuitValidation::findOne($id)->level->level_number;
    }
}
