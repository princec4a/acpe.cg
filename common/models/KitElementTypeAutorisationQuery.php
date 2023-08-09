<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitElementTypeAutorisation]].
 *
 * @see KitElementTypeAutorisation
 */
class KitElementTypeAutorisationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitElementTypeAutorisation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitElementTypeAutorisation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
