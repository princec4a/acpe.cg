<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_telephone".
 *
 * @property int $id
 * @property int $personneId
 * @property string $personne_type
 * @property string $numero
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 */
class KitTelephone extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_telephone';
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
            [['numero'], 'required'],
            [['personneId', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['personne_type'], 'string', 'max' => 1],
            [['numero'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'personneId' => Yii::t('app', 'Personne ID'),
            'numero' => Yii::t('app', 'Numero'),
            'personne_type' => Yii::t('app', 'Personne Type'),
            'created_at' => Yii::t('app', 'Create At'),
            'updated_at' => Yii::t('app', 'Update At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return KitTelephoneQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitTelephoneQuery(get_called_class());
    }
}
