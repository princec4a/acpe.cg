<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitDepartement]].
 *
 * @see KitDepartement
 */
class KitDepartementQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitDepartement[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitDepartement|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
