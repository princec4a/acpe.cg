<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_piece_fournir".
 *
 * @property int $id
 * @property string $code
 * @property string $nom
 * @property int $created_at
 * @property int $updated_at
     * @property int $created_by
 *
 * @property KitElementDemande[] $kitElementDemandes
 * @property KitElementTypeAutorisation[] $kitElementTypeAutorisations
 */
class KitPieceFournir extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_piece_fournir';
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
            [['code', 'nom'], 'required'],
            [['created_at', 'updated_at', 'created_by'], 'integer'],
            [['code'], 'string', 'max' => 10],
            [['nom'], 'string', 'max' => 255],
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
            'nom' => Yii::t('app', 'Name'),
            'created_at' => Yii::t('app', 'Create At'),
            'updated_at' => Yii::t('app', 'Update At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * Gets query for [[KitElementDemandes]].
     *
     * @return \yii\db\ActiveQuery|KitElementDemandeQuery
     */
    public function getKitElementDemandes()
    {
        return $this->hasMany(KitElementDemande::className(), ['piece_fournir_id' => 'id']);
    }

    /**
     * Gets query for [[KitElementTypeAutorisations]].
     *
     * @return \yii\db\ActiveQuery|KitElementTypeAutorisationQuery
     */
    public function getKitElementTypeAutorisations()
    {
        return $this->hasMany(KitElementTypeAutorisation::className(), ['piece_fournir_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return KitPieceFournirQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitPieceFournirQuery(get_called_class());
    }
}
