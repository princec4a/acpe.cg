<?php

namespace frontend\controllers;

use common\models\KitCircuitValidation;
use common\models\KitTypeAutorisationPiece;
use common\models\Model;

use Yii;
use common\models\KitTypeAutorisation;
use common\models\KitTypeAutorisationSearch;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Json;


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
                        'actions' => ['index', 'create', 'view', 'update', 'delete', 'getPieceFournirByTypeAuto'],
                        'roles' => ['@'],
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
                    print_r($e->getMessage());
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
        /*
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        */

        if ($model->load(Yii::$app->request->post())) {
            $oldIDs = ArrayHelper::map($modelsOptionValue, 'id', 'id');
            $modelsOptionValue = Model::createMultiple(KitCircuitValidation::class, $modelsOptionValue);
            Model::loadMultiple($modelsOptionValue, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsOptionValue, 'id', 'id')));

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
                    print_r($e->getMessage());
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
        $this->findModel($id)->delete();

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
            if(!is_null($model) && !empty($model)){
                foreach($model as $key =>$item){
                    $data[$key]['id'] = $item->piece_fournir_id;
                    $data[$key]['piece'] = $item->pieceFournir->nom;
                    $data[$key]['nombre'] = $item->nombre;
                    $data[$key]['ajoindre'] = $item->a_joindre;
                    $data[$key]['obligatoire'] = $item->obligatoire;
                }
            }else{
                $data[0]['id'] = 0;
                $data[0]['piece'] = 'Aucune pièce à fournir n\'est demandée';
                $data[0]['nombre'] = 0;
                $data[0]['ajoindre'] = 0;
                $data[0]['obligatoire'] = 0;
            }
            return Json::encode($data);
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
