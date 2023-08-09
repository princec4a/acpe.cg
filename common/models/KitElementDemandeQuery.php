<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitElementDemande]].
 *
 * @see KitElementDemande
 */
class KitElementDemandeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitElementDemande[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitElementDemande|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
