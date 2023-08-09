<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitTelephone]].
 *
 * @see KitTelephone
 */
class KitTelephoneQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitTelephone[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitTelephone|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
