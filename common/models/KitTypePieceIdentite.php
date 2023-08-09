<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_type_piece_identite".
 *
 * @property int $id
 * @property string $code
 * @property string $libelle
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property KitPersonneIdentite[] $kitPersonneIdentites
 */
class KitTypePieceIdentite extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_type_piece_identite';
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
            [['code', 'libelle'], 'required'],
            [['id', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['code'], 'string', 'max' => 10],
            [['libelle'], 'string', 'max' => 255],
            [['id'], 'unique'],
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
            'created_at' => Yii::t('app', 'Create At'),
            'updated_at' => Yii::t('app', 'Update At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * Gets query for [[KitPersonneIdentites]].
     *
     * @return \yii\db\ActiveQuery|KitPersonneIdentiteQuery
     */
    public function getKitPersonneIdentites()
    {
        return $this->hasMany(KitPersonneIdentite::className(), ['type_piece_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return KitTypePieceIdentiteQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitTypePieceIdentiteQuery(get_called_class());
    }
}
