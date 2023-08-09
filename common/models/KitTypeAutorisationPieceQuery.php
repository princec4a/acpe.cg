<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[KitTypeAutorisationPiece]].
 *
 * @see KitTypeAutorisationPiece
 */
class KitTypeAutorisationPieceQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return KitTypeAutorisationPiece[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return KitTypeAutorisationPiece|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
