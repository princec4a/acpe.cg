<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_type_autorisation_piece".
 *
 * @property int $id
 * @property int $type_autorisation_id
 * @property string|null $categorie
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property KitElementTypeAutorisation[] $kitElementTypeAutorisations
 * @property KitTypeAutorisation $typeAutorisation
 */
class KitTypeAutorisationPiece extends \yii\db\ActiveRecord
{
    const CAT_CADRE = "CADRE";
    const CAT_AGENT_MAIT = "AGENT DE MAITRISE";

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_type_autorisation_piece';
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
            [['type_autorisation_id'], 'required'],
            [['type_autorisation_id'], 'unique'],
            [['categorie'], 'string', 'max' => 20],
            [['type_autorisation_id', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['type_autorisation_id'], 'exist', 'skipOnError' => true, 'targetClass' => KitTypeAutorisation::className(), 'targetAttribute' => ['type_autorisation_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type_autorisation_id' => Yii::t('app', 'Type autorisation'),
            'categorie' => Yii::t('app', 'Categorie'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    public static function getCategoryArray()
    {
        return [
            ''=>Yii::t('app','-- selectionner la catégorie --'),
            self::CAT_CADRE => Yii::t('app', 'CADRE'),
            self::CAT_AGENT_MAIT => Yii::t('app', 'AGENT DE MAITRISE'),
        ];
    }

    /**
     * Gets query for [[KitElementTypeAutorisations]].
     *
     * @return \yii\db\ActiveQuery|KitElementTypeAutorisationQuery
     */
    public function getKitElementTypeAutorisations()
    {
        return $this->hasMany(KitElementTypeAutorisation::className(), ['type_autorisation_piece_id' => 'id']);
    }

    /**
     * Gets query for [[TypeAutorisation]].
     *
     * @return \yii\db\ActiveQuery|KitTypeAutorisationQuery
     */
    public function getTypeAutorisation()
    {
        return $this->hasOne(KitTypeAutorisation::className(), ['id' => 'type_autorisation_id']);
    }

    /**
     * {@inheritdoc}
     * @return KitTypeAutorisationPieceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitTypeAutorisationPieceQuery(get_called_class());
    }
}
