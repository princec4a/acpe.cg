<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_type_autorisation".
 *
 * @property int $id
 * @property string $code
 * @property string $libelle
 * @property float $duree_validite
 * @property string $type_duree
 * @property string $nationalite
 * @property int|null $signataire
 * @property float $prix
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property KitAutorisation[] $kitAutorisations
 * @property KitCircuitValidation[] $kitCircuitValidations
 * @property KitDemande[] $kitDemandes
 * @property KitTypeAutorisationPiece $kitTypeAutorisationPiece
 */
class KitTypeAutorisation extends \yii\db\ActiveRecord
{

    const DAY = 3600;
    const WEEK = self::DAY*7;
    const MONTH = self::WEEK*4;
    const YEAR = self::MONTH*12;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_type_autorisation';
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
            [['code', 'libelle', 'duree_validite', 'type_duree', 'nationalite'], 'required'],
            [['duree_validite', 'prix'], 'number'],
            [['signataire', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['code', 'libelle', 'nationalite'], 'string', 'max' => 255],
            [['type_duree'], 'string', 'max' => 10],
            [['code'], 'unique'],
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
            'libelle' => Yii::t('app', 'Libelle'),
            'duree_validite' => Yii::t('app', 'Duree Validite'),
            'type_duree' => Yii::t('app', 'Type Duree'),
            'nationalite' => Yii::t('app', 'Nationalite'),
            'signataire' => Yii::t('app', 'Signataire'),
            'prix' => Yii::t('app', 'Prix'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
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
        return $this->hasMany(KitAutorisation::className(), ['type_autorisation_id' => 'id']);
    }

    /**
     * Gets query for [[KitCircuitValidations]].
     *
     * @return \yii\db\ActiveQuery|KitCircuitValidationQuery
     */
    public function getKitCircuitValidations()
    {
        return $this->hasMany(KitCircuitValidation::className(), ['autorization_type_id' => 'id']);
    }

    /**
     * Gets query for [[KitDemandes]].
     *
     * @return \yii\db\ActiveQuery|KitDemandeQuery
     */
    public function getKitDemandes()
    {
        return $this->hasMany(KitDemande::className(), ['type_autorisation_id' => 'id']);
    }

    /**
     * Gets query for [[KitTypeAutorisationPiece]].
     *
     * @return \yii\db\ActiveQuery|KitTypeAutorisationPieceQuery
     */
    public function getKitTypeAutorisationPiece()
    {
        return $this->hasOne(KitTypeAutorisationPiece::className(), ['type_autorisation_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return KitTypeAutorisationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitTypeAutorisationQuery(get_called_class());
    }

    public static function getDataList(){
        $listData = [];
        $listArray = KitTypeAutorisation::find()
            ->orderBy('libelle')
            ->all();
        foreach($listArray as $item){
            $customerLibelle = $item->libelle . ' - '. $item->code;
            $listData[] = ["id" => $item->id, "libelle" => $customerLibelle];
        }
        return $listData;
    }

}
