<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitLevel]].
 *
 * @see KitLevel
 */
class KitLevelQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitLevel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitLevel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
