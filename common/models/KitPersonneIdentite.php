<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_personne_identite".
 *
 * @property int $id
 * @property int $personne_id
 * @property string $personne_type
 * @property int $type_piece_id
 * @property string $numero
 * @property string $date_emission
 * @property string $date_expiration
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 *
 * @property KitTypePieceIdentite $typePiece
 */
class KitPersonneIdentite extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_personne_identite';
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
            [['type_piece_id', 'numero'], 'required'],
            [['personne_id', 'type_piece_id', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['date_emission', 'date_expiration', 'created_at', 'updated_at'], 'safe'],
            //[['date_emission', 'date_expiration'], 'validateIdDocument'],
            [['numero'], 'string', 'max' => 255],
            [['personne_type'], 'string', 'max' => 1],
            [['type_piece_id'], 'exist', 'skipOnError' => true, 'targetClass' => KitTypePieceIdentite::className(), 'targetAttribute' => ['type_piece_id' => 'id']],
        ];
    }

    /*public function validateIdDocument($attribute, $params)
    {
        if (in_array($this->$attribute, [6,4,7])) {
            $this->addError($attribute, Yii::t('app','issue date and expiry date must be provided'));
        }
    }*/

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'personne_id' => Yii::t('app', 'Personne ID'),
            'personne_type' => Yii::t('app', 'Personne Type'),
            'type_piece_id' => Yii::t('app', 'Type ID'),
            'numero' => Yii::t('app', 'Number'),
            'date_emission' => Yii::t('app', 'Release date'),
            'date_expiration' => Yii::t('app', 'Expiration date'),
            'created_at' => Yii::t('app', 'Create At'),
            'updated_at' => Yii::t('app', 'Update At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    public function beforeSave($insert) {
        if($this->date_emission)
            $this->date_emission = Yii::$app->formatter->asDate($this->date_emission, 'yyyy-MM-dd');

        if($this->date_expiration)
            $this->date_expiration = Yii::$app->formatter->asDate($this->date_expiration, 'yyyy-MM-dd');


        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        parent::afterFind();

        if (!empty($this->date_emission))
            $this->date_emission = Yii::$app->formatter->asDate($this->date_emission, 'dd-MM-yyyy');

        if (!empty($this->date_expiration))
            $this->date_expiration = Yii::$app->formatter->asDate($this->date_expiration, 'dd-MM-yyyy');
    }

    /**
     * Gets query for [[TypePiece]].
     *
     * @return \yii\db\ActiveQuery|KitTypePieceIdentiteQuery
     */
    public function getTypePiece()
    {
        return $this->hasOne(KitTypePieceIdentite::className(), ['id' => 'type_piece_id']);
    }

    /**
     * {@inheritdoc}
     * @return KitPersonneIdentiteQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitPersonneIdentiteQuery(get_called_class());
    }
}
