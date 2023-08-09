<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_element_demande".
 *
 * @property int $id
 * @property int $piece_fournir_id
 * @property resource|null $fichier
 * @property string|null $file_name
 * @property int $demande_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property KitDemande $demande
 * @property KitPieceFournir $pieceFournir
 */
class KitElementDemande extends \yii\db\ActiveRecord
{

    public $files;
    public $files_id;
    public $files_name;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_element_demande';
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
            [['piece_fournir_id'], 'required'],
            [['piece_fournir_id', 'demande_id', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['fichier'], 'string'],
            [['files_id','files_name'], 'string'],
            [['file_name'], 'string', 'max' => 255],
            [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf, png, jpg'],
            [['created_at', 'updated_at'], 'safe'],
            [['demande_id'], 'exist', 'skipOnError' => true, 'targetClass' => KitDemande::className(), 'targetAttribute' => ['demande_id' => 'id']],
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
            'piece_fournir_id' => Yii::t('app', 'Piece Fournir ID'),
            'fichier' => Yii::t('app', 'Fichier'),
            'files_id' => Yii::t('app', 'File Id'),
            'files_name' => Yii::t('app', 'Files Name'),
            'files' => Yii::t('app', 'file'),
            'demande_id' => Yii::t('app', 'Demande ID'),
            'create_at' => Yii::t('app', 'Create At'),
            'update_at' => Yii::t('app', 'Update At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * Gets query for [[Demande]].
     *
     * @return \yii\db\ActiveQuery|KitDemandeQuery
     */
    public function getDemande()
    {
        return $this->hasOne(KitDemande::className(), ['id' => 'demande_id']);
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
     * {@inheritdoc}
     * @return KitElementDemandeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitElementDemandeQuery(get_called_class());
    }
}
