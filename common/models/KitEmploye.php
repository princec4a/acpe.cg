<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_employe".
 *
 * @property int $id
 * @property string $nom
 * @property string $photo
 * @property string $nom_jeune_fille
 * @property string $prenom
 * @property string $date_naissance
 * @property string|null $lieu_naissance
 * @property int $nationalite
 * @property string $email
 * @property string $sexe
 * @property int $entreprise_id
 * @property int|null $lieu_travail
 * @property int $ville_travail
 * @property string $statut_medical
 * @property string $type_contrat
 * @property string $reference_contrat
 * @property string $fonction
 * @property string $date_embauche
 * @property string|null $date_fin_contrat
 * @property string|null $categorie
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property KitAutorisation[] $kitAutorisations
 * @property KitDemande[] $kitDemandes
 * @property KitTelephone[] $kitTelephones
 * @property KitPersonneIdentite[] $kitPersonneIdentites
 * @property KitVille $villeTravail
 * @property KitEntreprise $entreprise
 * @property KitPays $nationalite0
 * @property KitDepartement $lieuTravail
 */
class KitEmploye extends \yii\db\ActiveRecord
{

    const CAT_CADRE = "CADRE";
    const CAT_AGENT_MAIT = "AGENT DE MAITRISE";

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_employe';
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
            [['nom', 'photo', 'prenom', 'date_naissance', 'lieu_naissance', 'nationalite', 'email', 'lieu_travail', 'categorie', 'type_contrat', 'fonction', 'date_embauche', 'sexe', 'entreprise_id'], 'required'],
            [['date_naissance', 'date_embauche', 'date_fin_contrat', 'created_at', 'updated_at'], 'safe'],
            [['lieu_naissance', 'nationalite', 'email', 'sexe', 'type_contrat'], 'string'],
            [['lieu_naissance', 'email','photo'], 'string'],
            [['nationalite', 'entreprise_id', 'ville_travail', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['sexe','type_contrat'], 'string', 'max' => 10],
            [['nom', 'nom_jeune_fille', 'prenom', 'lieu_travail', 'statut_medical', 'reference_contrat', 'fonction'], 'string', 'max' => 255],
            [['categorie'], 'string', 'max' => 20],
            ['date_naissance', 'validateBirthDay'],
            [['ville_travail'], 'exist', 'skipOnError' => true, 'targetClass' => KitVille::className(), 'targetAttribute' => ['ville_travail' => 'id']],
            [['entreprise_id'], 'exist', 'skipOnError' => true, 'targetClass' => KitEntreprise::className(), 'targetAttribute' => ['entreprise_id' => 'id']],
            [['nationalite'], 'exist', 'skipOnError' => true, 'targetClass' => KitPays::className(), 'targetAttribute' => ['nationalite' => 'id']],
            [['lieu_travail'], 'exist', 'skipOnError' => true, 'targetClass' => KitDepartement::className(), 'targetAttribute' => ['lieu_travail' => 'id']],
        ];
    }

    public function validateBirthDay($attribute, $params)
    {
        $date = Yii::$app->formatter->asDate($this->$attribute, 'yyyy-MM-dd');
         if (strtotime($date) >= strtotime(date('Y-m-d'))) {
             $this->addError($attribute, Yii::t('app','Birthdate can not be in future.'));
         }
    }

    public static function getCategoryArray()
    {
        return [
            ''=>'...'.Yii::t('app','select category').'...',
            self::CAT_CADRE => Yii::t('app', 'CADRE'),
            self::CAT_AGENT_MAIT => Yii::t('app', 'AGENT DE MAITRISE'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nom' => Yii::t('app', 'Name'),
            'photo' => Yii::t('app', 'Photo'),
            'nom_jeune_fille' => Yii::t('app', 'Maiden name'),
            'prenom' => Yii::t('app', 'First name'),
            'date_naissance' => Yii::t('app', 'Date of birth'),
            'lieu_naissance' => Yii::t('app', 'Place of birth'),
            'nationalite' => Yii::t('app', 'Nationality'),
            'email' => Yii::t('app', 'Email'),
            'sexe' => Yii::t('app', 'Sex'),
            'entreprise_id' => Yii::t('app', 'Company'),
            'lieu_travail' => Yii::t('app', 'Department'),
            'ville_travail' => Yii::t('app', 'Work city'),
            'statut_medical' => Yii::t('app', 'Health status'),
            'type_contrat_id' => Yii::t('app', 'Type of Contract'),
            'type_contrat' => Yii::t('app', 'Type of Contract'),
            'reference_contrat' => Yii::t('app', 'Contract reference'),
            'fonction' => Yii::t('app', 'Function'),
            'date_embauche' => Yii::t('app', 'Hiring date'),
            'date_fin_contrat' => Yii::t('app', 'Contract end date'),
            'categorie' => Yii::t('app', 'Category'),
            'create_at' => Yii::t('app', 'Create At'),
            'update_at' => Yii::t('app', 'Update At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * Gets query for [[KitAutorisations]].
     *
    'created_by' => Yii::t('app', 'Created By'),
     */
    public function getKitAutorisations()
    {
        return $this->hasMany(KitAutorisation::className(), ['employe_id' => 'id']);
    }

    /**
     * Gets query for [[kitTelephones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKitTelephones()
    {
        return $this->hasMany(KitTelephone::className(), ['personneId' => 'id'])
            ->andOnCondition(['personne_type' => 'P']);
    }

    /**
     * Gets query for [[kitPersonneIdentites]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKitPersonneIdentites()
    {
        return $this->hasMany(KitPersonneIdentite::className(), ['personne_id' => 'id'])
            ->andOnCondition(['personne_type' => 'P']);
    }

    /**
     * Gets query for [[KitDemandes]].
     *
     * @return \yii\db\ActiveQuery|KitDemandeQuery
     */
    public function getKitDemandes()
    {
        return $this->hasMany(KitDemande::className(), ['employe_id' => 'id']);
    }

    /**
     * Gets query for [[LieuTravail]].
     *
     * @return \yii\db\ActiveQuery|KitDepartementQuery
     */
    public function getLieuTravail()
    {
        return $this->hasOne(KitDepartement::className(), ['id' => 'lieu_travail']);
    }

    /**
     * Gets query for [[VilleTravail]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVilleTravail()
    {
        return $this->hasOne(KitVille::className(), ['id' => 'ville_travail']);
    }

    /**
     * Gets query for [[Entreprise]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEntreprise()
    {
        return $this->hasOne(KitEntreprise::className(), ['id' => 'entreprise_id']);
    }

    /**
     * Gets query for [[Nationalite0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNationalite0()
    {
        return $this->hasOne(KitPays::className(), ['id' => 'nationalite']);
    }

    public function beforeSave($insert) {

        if(!empty($this->date_naissance))
            $this->date_naissance =  Yii::$app->formatter->asDate($this->date_naissance, 'yyyy-MM-dd');

        if(!empty($this->date_embauche))
            $this->date_embauche = Yii::$app->formatter->asDate($this->date_embauche, 'yyyy-MM-dd');

        if(!empty($this->date_fin_contrat))
            $this->date_fin_contrat = Yii::$app->formatter->asDate($this->date_fin_contrat, 'yyyy-MM-dd');

        return parent::beforeSave($insert);
    }

    public function afterFind(){
        parent::afterFind();

        if(!empty($this->date_naissance))
            $this->date_naissance = Yii::$app->formatter->asDate($this->date_naissance, 'dd-MM-yyyy');

        if(!empty($this->date_embauche))
            $this->date_embauche = Yii::$app->formatter->asDate($this->date_embauche, 'dd-MM-yyyy');

        if(!empty($this->date_fin_contrat))
            $this->date_fin_contrat = Yii::$app->formatter->asDate($this->date_fin_contrat, 'dd-MM-yyyy');
    }

    public static function getDataList(){
        $listData = [];
        $listArray = KitEmploye::find()
            ->orderBy('nom')
            ->all();
        foreach($listArray as $item){
            $nom = $item->nom . ' '. $item->prenom;
            $listData[] = ["id" => $item->id, "nom" => $nom];
        }
        return $listData;
    }

    public static function getDataListEnterprise($id){
        $listData = [];
        $listArray = KitEmploye::find()
            ->where(['entreprise_id'=>$id])
            ->orderBy('nom')
            ->all();
        foreach($listArray as $item){
            $nom = $item->nom . ' '. $item->prenom;
            $listData[] = ["id" => $item->id, "nom" => $nom];
        }
        return $listData;
    }

    /**
     * {@inheritdoc}
     * @return KitEmployeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitEmployeQuery(get_called_class());
    }

}
