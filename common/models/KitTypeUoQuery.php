<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitTypeUo]].
 *
 * @see KitTypeUo
 */
class KitTypeUoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitTypeUo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitTypeUo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
