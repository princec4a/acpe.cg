<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitPieceFournir]].
 *
 * @see KitPieceFournir
 */
class KitPieceFournirQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitPieceFournir[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitPieceFournir|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
