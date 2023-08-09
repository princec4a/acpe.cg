<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_uo".
 *
 * @property int $id
 * @property string $code
 * @property string $libelle
 * @property int $type
 * @property int|null $parent
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property KitTypeUo $type0
 * @property KitUo $parent0
 * @property KitUo[] $kitUos
 */
class KitUo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_uo';
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
            [['code', 'libelle', 'type'], 'required'],
            [['type', 'parent', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['code', 'libelle'], 'string', 'max' => 255],
            [['type'], 'exist', 'skipOnError' => true, 'targetClass' => KitTypeUo::className(), 'targetAttribute' => ['type' => 'id']],
            [['parent'], 'exist', 'skipOnError' => true, 'targetClass' => KitUo::className(), 'targetAttribute' => ['parent' => 'id']],
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
            'libelle' => Yii::t('app', 'Libelle'),
            'type' => Yii::t('app', 'Type'),
            'parent' => Yii::t('app', 'Parent'),
            'created_at' => Yii::t('app', 'Create At'),
            'updated_at' => Yii::t('app', 'Update At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * Gets query for [[Type0]].
     *
     * @return \yii\db\ActiveQuery|KitTypeUoQuery
     */
    public function getType0()
    {
        return $this->hasOne(KitTypeUo::className(), ['id' => 'type']);
    }

    /**
     * Gets query for [[Parent0]].
     *
     * @return \yii\db\ActiveQuery|KitUoQuery
     */
    public function getParent0()
    {
        return $this->hasOne(KitUo::className(), ['id' => 'parent']);
    }

    /**
     * Gets query for [[KitUos]].
     *
     * @return \yii\db\ActiveQuery|KitUoQuery
     */
    public function getKitUos()
    {
        return $this->hasMany(KitUo::className(), ['parent' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return KitUoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitUoQuery(get_called_class());
    }
}
