<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitPays]].
 *
 * @see KitPays
 */
class KitPaysQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitPays[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitPays|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
