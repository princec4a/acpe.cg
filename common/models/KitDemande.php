<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_demande".
 *
 * @property int $id
 * @property string $code
 * @property string $date_reception
 * @property int $employe_id
 * @property int $type_autorisation_id
 * @property int $statut
 * @property float $prix
 * @property resource|null $file
 * @property string|null $file_name
 * @property int|null $signed_date
 * @property int|null $expiration_date
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property KitAutorisation[] $kitAutorisations
 * @property KitEmploye $employe
 * @property KitTypeAutorisation $typeAutorisation
 * @property KitElementDemande[] $kitElementDemandes
 * @property KitRejet[] $kitRejets
 * @property KitValidationDemande[] $kitValidationDemandes
 */
class KitDemande extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_demande';
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
            [['employe_id', 'type_autorisation_id'], 'required'],
            [['employe_id', 'type_autorisation_id', 'statut', 'created_at', 'updated_at', 'created_by', 'signed_date', 'expiration_date'], 'integer'],
            [['date_reception', 'created_at', 'updated_at', 'signed_date', 'expiration_date'], 'safe'],
            [['file'], 'string'],
            [['code', 'file_name'], 'string', 'max' => 255],
            [['code'], 'unique'],
            [['prix'], 'number'],
            [['employe_id'], 'exist', 'skipOnError' => true, 'targetClass' => KitEmploye::className(), 'targetAttribute' => ['employe_id' => 'id']],
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
            'code' => Yii::t('app', 'Code'),
            'date_reception' => Yii::t('app', 'Date de réception'),
            'employe_id' => Yii::t('app', 'Employé'),
            'type_autorisation_id' => Yii::t('app', 'Type d\'autorisation'),
            'statut' => Yii::t('app', 'Statut'),
            'prix' => Yii::t('app', 'Prix'),
            'file' => Yii::t('app', 'File'),
            'file_name' => Yii::t('app', 'File Name'),
            'expiration_date' => Yii::t('app', 'Expiration Date'),
            'signed_date' => Yii::t('app', 'Signed Date'),
            'created_at' => Yii::t('app', 'Create At'),
            'updated_at' => Yii::t('app', 'Update At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * Gets query for [[KitAutorisations]].
     *
     * @return \yii\db\ActiveQuery|KitAutorisationQuery
     */
    public function getKitAutorisations()
    {
        return $this->hasMany(KitAutorisation::className(), ['demande_id' => 'id']);
    }

    /**
     * Gets query for [[Employe]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmploye()
    {
        return $this->hasOne(KitEmploye::className(), ['id' => 'employe_id']);
    }

    /**
     * Gets query for [[TypeAutorisation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTypeAutorisation()
    {
        return $this->hasOne(KitTypeAutorisation::className(), ['id' => 'type_autorisation_id']);
    }

    /**
     * Gets query for [[KitElementDemandes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKitElementDemandes()
    {
        return $this->hasMany(KitElementDemande::className(), ['demande_id' => 'id']);
    }

    public function beforeSave($insert) {
        if(!empty($this->date_reception))
            $this->date_reception = Yii::$app->formatter->asDate($this->date_reception, 'yyyy-MM-dd');

        return parent::beforeSave($insert);
    }

    public function afterFind(){
        parent::afterFind();

        if(!empty($this->date_reception))
            $this->date_reception = Yii::$app->formatter->asDate($this->date_reception, 'dd-MM-yyyy');
    }

    /**
     * {@inheritdoc}
     * @return KitDemandeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitDemandeQuery(get_called_class());
    }

    /**
     * Gets query for [[KitRejets]].
     *
     * @return \yii\db\ActiveQuery|KitRejetQuery
     */
    public function getKitRejets()
    {
        return $this->hasMany(KitRejet::className(), ['demande_id' => 'id']);
    }

    /**
     * Gets query for [[KitValidationDemandes]].
     *
     * @return \yii\db\ActiveQuery|KitValidationDemandeQuery
     */
    public function getKitValidationDemandes()
    {
        return $this->hasMany(KitValidationDemande::className(), ['demande_id' => 'id']);
    }

    public function beforeDelete() {
        foreach($this->kitElementDemandes as $item){
            $item->delete();
        }
        return parent::beforeDelete();
    }

}
