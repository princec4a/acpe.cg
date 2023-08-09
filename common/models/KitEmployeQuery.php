<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitEmploye]].
 *
 * @see KitEmploye
 */
class KitEmployeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitEmploye[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitEmploye|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
