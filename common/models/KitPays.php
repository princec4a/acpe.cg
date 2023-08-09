<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_pays".
 *
 * @property int $id
 * @property int|null $id_continent
 * @property string $nompays
 *
 * @property KitEmploye[] $kitEmployes
 */
class KitPays extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_pays';
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
            [['id_continent'], 'integer'],
            [['nompays'], 'required'],
            [['nompays'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_continent' => Yii::t('app', 'Id Continent'),
            'nompays' => Yii::t('app', 'Nompays'),
        ];
    }

    /**
     * Gets query for [[KitEmployes]].
     *
     * @return \yii\db\ActiveQuery|KitEmployeQuery
     */
    public function getKitEmployes()
    {
        return $this->hasMany(KitEmploye::className(), ['nationalite' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return KitPaysQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitPaysQuery(get_called_class());
    }
}
