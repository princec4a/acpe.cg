<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "kit_activity".
 *
 * @property int $id
 * @property string $code
 * @property string $description
 *
 * @property KitEntreprise[] $kitEntreprises
 */
class KitActivity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_activity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'description'], 'required'],
            [['code', 'description'], 'string', 'max' => 255],
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
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * Gets query for [[KitEntreprises]].
     *
     * @return \yii\db\ActiveQuery|KitEntrepriseQuery
     */
    public function getKitEntreprises()
    {
        return $this->hasMany(KitEntreprise::className(), ['activite_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return KitActivityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitActivityQuery(get_called_class());
    }
}
