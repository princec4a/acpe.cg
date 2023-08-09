<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitHistoriqueTrajet]].
 *
 * @see KitHistoriqueTrajet
 */
class KitHistoriqueTrajetQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitHistoriqueTrajet[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitHistoriqueTrajet|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
