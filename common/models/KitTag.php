<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "kit_tag".
 *
 * @property int $id
 * @property string $tag
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 */
class KitTag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tag', 'created_at', 'updated_at', 'created_by'], 'required'],
            [['created_at', 'updated_at', 'created_by'], 'integer'],
            [['tag'], 'string', 'max' => 20],
            [['tag'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tag' => Yii::t('app', 'Tag'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return KitTagQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitTagQuery(get_called_class());
    }
}
