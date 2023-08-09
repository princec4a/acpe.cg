<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_report".
 *
 * @property int $id
 * @property string $name
 * @property string $header
 * @property string $body
 * @property string $footer
 * @property int|null $level_id
 * @property int $action
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 *
 * @property KitLevel $level
 */
class KitReport extends \yii\db\ActiveRecord
{

    const ACTION_VALIDATE = 5;
    const ACTION_REJECT = self::ACTION_VALIDATE * 10;
    const ACTION_RENEW = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_report';
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
            [['name', 'header', 'body', 'level_id', 'action'], 'required'],
            [['header', 'body', 'footer'], 'string'],
            [['action', 'level_id', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['level_id'], 'exist', 'skipOnError' => true, 'targetClass' => KitLevel::className(), 'targetAttribute' => ['level_id' => 'id']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'header' => Yii::t('app', 'Header'),
            'body' => Yii::t('app', 'Body'),
            'footer' => Yii::t('app', 'Footer'),
            'action' => Yii::t('app', 'Action'),
            'level_id' => Yii::t('app', 'Level'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return KitReportQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitReportQuery(get_called_class());
    }

    public static function getActionArray()
    {
        return [
            self::ACTION_REJECT => Yii::t('app', 'Reject'),
            self::ACTION_RENEW => Yii::t('app', 'Renew'),
            self::ACTION_VALIDATE => Yii::t('app', 'Validate'),
        ];
    }

    public static function getActionALabel($id)
    {
        foreach(KitReport::getActionArray() as $item => $val){
            if ($item == $id)
                return $val;
        };
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
}
