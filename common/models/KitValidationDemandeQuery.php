<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitValidationDemande]].
 *
 * @see KitValidationDemande
 */
class KitValidationDemandeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitValidationDemande[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitValidationDemande|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
