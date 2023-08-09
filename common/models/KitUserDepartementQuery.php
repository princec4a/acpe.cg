<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitUserDepartement]].
 *
 * @see KitUserDepartement
 */
class KitUserDepartementQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitUserDepartement[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitUserDepartement|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
