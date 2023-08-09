<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitTypeAutorisation]].
 *
 * @see KitTypeAutorisation
 */
class KitTypeAutorisationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitTypeAutorisation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitTypeAutorisation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
