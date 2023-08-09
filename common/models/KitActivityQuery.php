<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitActivity]].
 *
 * @see KitActivity
 */
class KitActivityQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitActivity[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitActivity|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
