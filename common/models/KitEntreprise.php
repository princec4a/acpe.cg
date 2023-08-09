<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_entreprise".
 *
 * @property int $id
 * @property string $raison_sociale
 * @property string $email
 * @property string $adresse_congo
 * @property string $logo
 * @property int $ville_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property string $sigle
 * @property string $niu
 * @property int $activite_id
 * @property string $legal_representative_name
 * @property string $legal_representative_givename
 * @property string $fonction
 * @property string $sex
 * @property string $telephone
 * @property string $contact_name
 * @property string $contact_poste
 * @property string $contact_sex
 * @property int|null $user_id
 *
 * @property KitEmploye[] $kitEmployes
 * @property User $user
 */
class KitEntreprise extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_entreprise';
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
            //[['raison_sociale', 'email', 'adresse_congo', 'ville_id', 'legal_representative_name', 'legal_representative_givename', 'fonction', 'sex', 'telephone'], 'required'],
            [['email', 'adresse_congo', 'logo'], 'string'],
            [['ville_id', 'created_at', 'updated_at', 'created_by', 'activite_id', 'user_id'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['raison_sociale', 'sigle', 'legal_representative_name', 'legal_representative_givename', 'fonction', 'contact_name', 'contact_poste'], 'string', 'max' => 255],
            [['niu'], 'string', 'max' => 17],
            [['sex', 'contact_sex'], 'string', 'max' => 1],
            [['raison_sociale','niu'], 'unique'],
            [['telephone'], 'string', 'max' => 9],
            ['email', 'email'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'raison_sociale' => Yii::t('app', 'Company name'),
            'email' => Yii::t('app', 'Email'),
            'adresse_congo' => Yii::t('app', 'Address in Congo'),
            'logo' => Yii::t('app', 'Logo'),
            'ville_id' => Yii::t('app', 'Ville'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'sigle' => Yii::t('app', 'Acronym'),
            'niu' => Yii::t('app', 'Niu'),
            'activite_id' => Yii::t('app', 'Activities'),
            'legal_representative_name' => Yii::t('app', 'Legal Representative Name'),
            'legal_representative_givename' => Yii::t('app', 'Legal Representative Givename'),
            'fonction' => Yii::t('app', 'Fonction'),
            'sex' => Yii::t('app', 'Sex'),
            'telephone' => Yii::t('app', 'Telephone'),
            'contact_name' => Yii::t('app', 'Contact Name'),
            'contact_poste' => Yii::t('app', 'Contact Poste'),
            'contact_sex' => Yii::t('app', 'Contact Sex'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }

    /**
     * Gets query for [[KitEmployes]].
     *
     * @return \yii\db\ActiveQuery|KitEmployeQuery
     */
    public function getKitEmployes()
    {
        return $this->hasMany(KitEmploye::className(), ['entreprise_id' => 'id']);
    }


    public static function getKitPersonneIdentites($id)
    {
        return KitPersonneIdentite::findAll(['personne_id' => $id, 'personne_type' => 'M']);
    }

    public static function getVille($id)
    {
        return KitVille::findOne(['id' => $id]);
    }

    public static function getTelephones($id)
    {
        return KitTelephone::findAll(['personneId' => $id, 'personne_type' => 'M']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return KitEntrepriseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitEntrepriseQuery(get_called_class());
    }
}
