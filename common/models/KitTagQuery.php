<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitTag]].
 *
 * @see KitTag
 */
class KitTagQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitTag[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitTag|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
