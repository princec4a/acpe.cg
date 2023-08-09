<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_element_dossier_physique".
 *
 * @property int $id
 * @property int $piece_fournie_id
 * @property int $nombre
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $acknowledgment_id
 *
 * @property KitAcknowledgment $acknowledgment
 * @property KitPieceFournir $pieceFournie
 */
class KitElementDossierPhysique extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_element_dossier_physique';
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
            [['piece_fournie_id', 'acknowledgment_id'], 'required'],
            [['piece_fournie_id', 'nombre', 'created_at', 'updated_at', 'created_by', 'acknowledgment_id'], 'integer'],
            [['acknowledgment_id'], 'exist', 'skipOnError' => true, 'targetClass' => KitAcknowledgment::className(), 'targetAttribute' => ['acknowledgment_id' => 'id']],
            [['piece_fournie_id'], 'exist', 'skipOnError' => true, 'targetClass' => KitPieceFournir::className(), 'targetAttribute' => ['piece_fournie_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'piece_fournie_id' => Yii::t('app', 'Piece Fournie ID'),
            'nombre' => Yii::t('app', 'Nombre'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'acknowledgment_id' => Yii::t('app', 'Acknowledgment ID'),
        ];
    }

    /**
     * Gets query for [[Acknowledgment]].
     *
     * @return \yii\db\ActiveQuery|KitAcknowledgmentQuery
     */
    public function getAcknowledgment()
    {
        return $this->hasOne(KitAcknowledgment::className(), ['id' => 'acknowledgment_id']);
    }

    /**
     * Gets query for [[PieceFournie]].
     *
     * @return \yii\db\ActiveQuery|KitPieceFournirQuery
     */
    public function getPieceFournie()
    {
        return $this->hasOne(KitPieceFournir::className(), ['id' => 'piece_fournie_id']);
    }

    /**
     * {@inheritdoc}
     * @return KitElementDossierPhysiqueQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitElementDossierPhysiqueQuery(get_called_class());
    }
}
