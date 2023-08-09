<?php

namespace frontend\controllers;

use common\models\KitDemande;

use Yii;
use yii\rest\ActiveController;

/**
 * DemandeController implements the CRUD actions for KitDemande model.
 */
class DemandeRestController extends ActiveController
{
    public $modelClass = KitDemande::class;
}
