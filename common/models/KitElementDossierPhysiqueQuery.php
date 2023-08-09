<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitElementDossierPhysique]].
 *
 * @see KitElementDossierPhysique
 */
class KitElementDossierPhysiqueQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitElementDossierPhysique[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitElementDossierPhysique|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
