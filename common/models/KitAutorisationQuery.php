<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitAutorisation]].
 *
 * @see KitAutorisation
 */
class KitAutorisationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitAutorisation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitAutorisation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
