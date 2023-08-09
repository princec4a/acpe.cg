<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitAcknowledgment]].
 *
 * @see KitAcknowledgment
 */
class KitAcknowledgmentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitAcknowledgment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitAcknowledgment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
