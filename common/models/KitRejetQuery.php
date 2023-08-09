<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitRejet]].
 *
 * @see KitRejet
 */
class KitRejetQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitRejet[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitRejet|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
