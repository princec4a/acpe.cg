<?php

namespace backend\modules\settings\controllers;

use common\models\KitCircuitValidation;
use common\models\KitDemande;
use common\models\KitElementTypeAutorisation;
use common\models\KitTypeAutorisationPiece;
use common\models\Model;

use Yii;
use common\models\KitTypeAutorisation;
use common\models\KitTypeAutorisationSearch;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;


/**
 * TypeAutorisationController implements the CRUD actions for KitTypeAutorisation model.
 */
class TypeAutorisationController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'view', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['typeAutotisationIndex'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['typeAutotisationCreate'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['typeAutotisationView'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('typeAutotisationUpdate', ['user' => $this->findModel(Yii::$app->request->get('id'))]);
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['typeAutotisationDelete'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all KitTypeAutorisation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KitTypeAutorisationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single KitTypeAutorisation model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new KitTypeAutorisation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KitTypeAutorisation();
        $modelsOptionValue = [new KitCircuitValidation];

        /*
        if ($model->load(Yii::$app->request->post()) && $model->save()) {            ;
            Yii::$app->session->setFlash('success', Yii::t('app', 'Type d\'autorisation : '.$model->code.' enregistré avec succès'));
            return $this->redirect(['create', 'id' => $model->id]);
        }
        */

        if ($model->load(Yii::$app->request->post())) {
            $modelsOptionValue = Model::createMultiple(KitCircuitValidation::class);
            Model::loadMultiple($modelsOptionValue, Yii::$app->request->post());
            $modelsOptionValue = $this->removeNullItem($modelsOptionValue);

            /*
            foreach($modelsOptionValue as $model){
                echo "<pre>";
                var_dump($model->autorization_type_id);
                echo "</pre>";
            }
            exit;
            */

            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($modelsOptionValue),
                    ActiveForm::validate($model)
                );
            }

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsOptionValue) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        foreach ($modelsOptionValue as $modelValue) {
                            $modelValue->autorization_type_id = $model->id;
                            if (! ($flag = $modelValue->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('error', $e->getMessage());
                }
            }

            /*echo "<pre>";
            var_dump($modelsOptionValue);
            echo "</pre>";
            exit;*/
        }

        return $this->render('create', [
            'model' => $model,
            'modelsOptionValue' => (empty($modelsOptionValue)) ? [new KitCircuitValidation()] : $modelsOptionValue
        ]);
    }

    /**
     * Updates an existing KitTypeAutorisation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelsOptionValue = $model->kitCircuitValidations;



        if ($model->load(Yii::$app->request->post())) {
            $oldIDs = ArrayHelper::map($modelsOptionValue, 'id', 'id');

            /*echo "<pre>";
            var_dump($oldIDs);
            echo "</pre>";

            echo '<br />';*/

            $modelsOptionValue = Model::createMultiple(KitCircuitValidation::className(), [new KitCircuitValidation]);

            Model::loadMultiple($modelsOptionValue, Yii::$app->request->post());

/*            foreach($modelsOptionValue as $v){
                echo "<pre>";
                var_dump($v->level_id);
                echo "</pre>";
            }*/

            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsOptionValue, 'id', 'id')));
/*
            //foreach($modelsOptionValue as $v){
                echo "<pre>";
                var_dump($deletedIDs);
                echo "</pre>";
            //}
            exit;*/

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsOptionValue) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (!empty($deletedIDs)) {
                            KitCircuitValidation::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelsOptionValue as $modelValue) {
                            $modelValue->autorization_type_id = $model->id;
                            if (! ($flag = $modelValue->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('error', $e->getMessage());
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelsOptionValue' => (empty($modelsOptionValue)) ? [new KitCircuitValidation()] : $modelsOptionValue
        ]);
    }

    /**
     * Deletes an existing KitTypeAutorisation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        try{
            $this->findModel($id)->delete();
        }catch (\Exception $e){
            Yii::$app->getSession()->setFlash('error', 'Veuillez au préalable supprimer toutes les données liées à ce type d\'autorisation SVP !');
        }


        return $this->redirect(['index']);
    }

    /**
     * Finds the KitTypeAutorisation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KitTypeAutorisation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KitTypeAutorisation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionGetPieceFournirByTypeAuto()
    {
        $data = array();
        if (Yii::$app->request->isPost){
            $id = Yii::$app->request->post('typeAutorisation');
            $model = KitTypeAutorisationPiece::findOne(['type_autorisation_id'=>$id])->kitElementTypeAutorisations;
            foreach($model as $key =>$item){
                $data[$key]['id'] = $item->piece_fournir_id;
                $data[$key]['piece'] = $item->pieceFournir->nom;
                $data[$key]['nombre'] = $item->nombre;
                $data[$key]['ajoindre'] = $item->a_joindre;
                $data[$key]['obligatoire'] = $item->obligatoire;
            }
            return \yii\helpers\Json::encode($data);
        }
        /*$data = Yii::$app->request->post('typeAutorisation');
        if (isset($data)) {
            $test = "Ajax Worked!";
        } else {
            $test = "Ajax failed";
        }
        return \yii\helpers\Json::encode($test);*/
    }

    public function actionGetPieceByDemande()
    {
        $data = array();
        if (Yii::$app->request->isPost){
            $id = Yii::$app->request->post('demande');
            $name = KitDemande::findOne(['id'=>$id])->employe->nom .' '.KitDemande::findOne(['id'=>$id])->employe->prenom;
            $autorisation = KitDemande::findOne(['id'=>$id])->typeAutorisation->libelle;
            $typeAuto = KitDemande::findOne(['id'=>$id])->type_autorisation_id;
            try{
                $model = KitTypeAutorisationPiece::findOne(['type_autorisation_id'=>$typeAuto])->kitElementTypeAutorisations;
                foreach($model as $key =>$item){
                    $data[$key]['id'] = $item->piece_fournir_id;
                    $data[$key]['piece'] = $item->pieceFournir->nom;
                    $data[$key]['nombre'] = $item->nombre;
                    $data[$key]['ajoindre'] = $item->a_joindre;
                    $data[$key]['obligatoire'] = $item->obligatoire;
                    $data[$key]['name'] = $name;
                    $data[$key]['autorisation'] = $autorisation;
                }
            }catch (\Exception $e){
                $data[0]['id'] = '';
                $data[0]['piece'] = '';
                $data[0]['nombre'] = '';
                $data[0]['ajoindre'] = '';
                $data[0]['obligatoire'] = '';
                $data[0]['name'] = $name;
                $data[0]['autorisation'] = $autorisation;
            }
            return \yii\helpers\Json::encode($data);
        }
    }

    protected function removeNullItem($modelsOptionValue){
        foreach ($modelsOptionValue as $i => $model) {
            if(is_null($model->level_id))
                unset($modelsOptionValue[$i]);
        }
        return $modelsOptionValue;
    }
}
