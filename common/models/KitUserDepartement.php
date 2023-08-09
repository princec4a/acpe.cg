<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "kit_user_departement".
 *
 * @property int $id
 * @property int $user_id
 * @property int $departement_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property User $user
 * @property KitDepartement $departement
 */
class KitUserDepartement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kit_user_departement';
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
            [['departement_id'], 'required'],
            [['user_id', 'departement_id', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['departement_id'], 'exist', 'skipOnError' => true, 'targetClass' => KitDepartement::className(), 'targetAttribute' => ['departement_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'Utilisateur'),
            'departement_id' => Yii::t('app', 'Departement'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|User
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Departement]].
     *
     * @return \yii\db\ActiveQuery|KitDepartementQuery
     */
    public function getDepartement()
    {
        return $this->hasOne(KitDepartement::className(), ['id' => 'departement_id']);
    }

    /**
     * {@inheritdoc}
     * @return KitUserDepartementQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KitUserDepartementQuery(get_called_class());
    }
}
