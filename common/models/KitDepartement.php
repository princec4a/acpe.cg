<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_departement".
 *
 * @property int $id
 * @property string $nom
 * @property string $code
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 *
 * @property KitVille[] $kitVilles
 */
class KitDepartement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_departement';
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
            [['nom', 'code'], 'required'],
            [['created_at', 'updated_at', 'created_by'], 'integer'],
            [['nom'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 10],
            [['code'], 'unique'],
            [['nom'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nom' => Yii::t('app', 'Nom'),
            'code' => Yii::t('app', 'Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * Gets query for [[KitVilles]].
     *
     * @return \yii\db\ActiveQuery|KitVilleQuery
     */
    public function getKitVilles()
    {
        return $this->hasMany(KitVille::className(), ['departement_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return KitDepartementQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitDepartementQuery(get_called_class());
    }
}
