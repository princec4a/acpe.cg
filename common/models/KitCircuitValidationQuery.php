<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitCircuitValidation]].
 *
 * @see KitCircuitValidation
 */
class KitCircuitValidationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitCircuitValidation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitCircuitValidation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
