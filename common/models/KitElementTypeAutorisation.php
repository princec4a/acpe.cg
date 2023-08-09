<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_element_type_autorisation".
 *
 * @property int $id
 * @property int $piece_fournir_id
 * @property int $type_autorisation_piece_id
 * @property int $nombre
 * @property int $a_joindre
 * @property int $obligatoire
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property string $date_effective
 * @property string $date_fin
 *
 * @property KitPieceFournir $pieceFournir
 * @property KitTypeAutorisationPiece $typeAutorisationPiece

 */
class KitElementTypeAutorisation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_element_type_autorisation';
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
            [['piece_fournir_id', 'nombre', 'a_joindre', 'obligatoire'], 'required'],
            [['piece_fournir_id', 'type_autorisation_piece_id', 'nombre', 'a_joindre', 'obligatoire', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['date_effective', 'date_fin'], 'safe'],
            [['type_autorisation_piece_id'], 'exist', 'skipOnError' => true, 'targetClass' => KitTypeAutorisationPiece::className(), 'targetAttribute' => ['type_autorisation_piece_id' => 'id']],
            [['piece_fournir_id'], 'exist', 'skipOnError' => true, 'targetClass' => KitPieceFournir::className(), 'targetAttribute' => ['piece_fournir_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'piece_fournir_id' => Yii::t('app', 'Piece à fournir'),
            'type_autorisation_piece_id' => Yii::t('app', 'Type Autorisation'),
            'nombre' => Yii::t('app', 'Nombre'),
            'a_joindre' => Yii::t('app', 'A Joindre'),
            'obligatoire' => Yii::t('app', 'Obligatoire'),
            'create_at' => Yii::t('app', 'Created At'),
            'update_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'date_effective' => Yii::t('app', 'Date Effective'),
            'date_fin' => Yii::t('app', 'Date Fin'),
        ];
    }

    /**
     * Gets query for [[PieceFournir]].
     *
     * @return \yii\db\ActiveQuery|KitPieceFournirQuery
     */
    public function getPieceFournir()
    {
        return $this->hasOne(KitPieceFournir::className(), ['id' => 'piece_fournir_id']);
    }

    /**
     * Gets query for [[TypeAutorisationPiece]].
     *
     * @return \yii\db\ActiveQuery|KitTypeAutorisationPieceQuery
     */
    public function getTypeAutorisationPiece()
    {
        return $this->hasOne(KitTypeAutorisationPiece::className(), ['id' => 'type_autorisation_piece_id']);
    }


    /**
     * {@inheritdoc}
     * @return KitElementTypeAutorisationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitElementTypeAutorisationQuery(get_called_class());
    }
}
