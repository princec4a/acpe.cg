<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitVille]].
 *
 * @see KitVille
 */
class KitVilleQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitVille[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitVille|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
