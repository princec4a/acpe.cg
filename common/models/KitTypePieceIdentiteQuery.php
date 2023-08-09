<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitTypePieceIdentite]].
 *
 * @see KitTypePieceIdentite
 */
class KitTypePieceIdentiteQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitTypePieceIdentite[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitTypePieceIdentite|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
