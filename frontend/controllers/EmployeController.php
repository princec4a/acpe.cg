<?php

namespace frontend\controllers;

use common\models\KitEntreprise;
use kartik\form\ActiveForm;
use Yii;
use common\models\KitEmploye;
use common\models\KitEmployeSearch;
use common\models\Model;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\KitTelephone;
use common\models\KitPersonneIdentite;

/**
 * EmployeController implements the CRUD actions for KitEmploye model.
 */
class EmployeController extends Controller
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
                        'actions' => ['index', 'create', 'view', 'update', 'delete'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'uploadPhoto' => [
                'class' => '\budyaga\cropper\actions\UploadAction',
                'url' => 'http://localhost:90/acpe.cg/frontend/web/uploads/user/photo', //Yii::$app->controller->module->userPhotoUrl,
                //'url' => 'http://acpe.ekreatic.com/backend/web/uploads/user/photo',
                'path' => '@frontend/web/uploads/user/photo', //Yii::$app->controller->module->userPhotoPath,
            ]
        ];
    }

    /**
     * Lists all KitEmploye models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KitEmployeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single KitEmploye model.
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
     * Creates a new KitEmploye model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KitEmploye();
        $enterprise = KitEntreprise::find()->where(['user_id' => \Yii::$app->user->id])->one();
        (!is_null($enterprise))? $model->entreprise_id = $enterprise->id : $model->entreprise_id = null;
        $modelsOptionValue = [new KitPersonneIdentite];
        $modelsTelephone = [new KitTelephone];

        if ($model->load(Yii::$app->request->post())) {
            $modelsOptionValue = Model::createMultiple(KitPersonneIdentite::classname());
            $modelsTelephone = Model::createMultiple(KitTelephone::classname());
            Model::loadMultiple($modelsOptionValue, Yii::$app->request->post());
            Model::loadMultiple($modelsTelephone, Yii::$app->request->post());

            //if(is_null($model->date_fin_contrat) || empty($model->date_fin_contrat))
            //    $model->date_fin_contrat = '0000-00-00';

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsOptionValue) && Model::validateMultiple($modelsTelephone) && $valid;

            if($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save()) {
                        foreach ($modelsOptionValue as $modelValue) {
                            $modelValue->personne_id = $model->id;
                            $modelValue->personne_type = 'P';

                            if (!($flag = $modelValue->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                        foreach ($modelsTelephone as $modelValue) {
                            $modelValue->personneId = $model->id;
                            $modelValue->personne_type = 'P';

                            if (!($flag = $modelValue->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }

                } catch (Exception $e) {
                    Yii::$app->session->setFlash('error', $e->getMessage());
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'modelsOptionValue' => (empty($modelsOptionValue)) ? [new KitPersonneIdentite] : $modelsOptionValue,
            'modelsTelephone' => (empty($modelsTelephone)) ? [new KitTelephone] : $modelsTelephone
        ]);
    }

    /**
     * Updates an existing KitEmploye model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelsOptionValue = $model->kitPersonneIdentites;
        $modelsTelephone = $model->kitTelephones;

        if ($model->load(Yii::$app->request->post())) {
            $oldIDs = ArrayHelper::map($modelsOptionValue, 'id', 'id');
            $modelsOptionValue = Model::createMultiple(KitPersonneIdentite::classname(), $modelsOptionValue);
            Model::loadMultiple($modelsOptionValue, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsOptionValue, 'id', 'id')));

            $oldPhoneIDs = ArrayHelper::map($modelsTelephone, 'id', 'id');
            $modelsTelephone = Model::createMultiple(KitTelephone::classname(), $modelsTelephone);
            Model::loadMultiple($modelsTelephone, Yii::$app->request->post());
            $deletedPhoneIDs = array_diff($oldPhoneIDs, array_filter(ArrayHelper::map($modelsTelephone, 'id', 'id')));

            /*echo "<pre>";
            var_dump($modelsOptionValue);
            echo "</pre>";
            exit;*/
            // ajax validation
            /*if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($modelsOptionValue),
                    ActiveForm::validateMultiple($modelsTelephone),
                    ActiveForm::validate($model)
                );
            }*/

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsOptionValue) && Model::validateMultiple($modelsTelephone) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {

                        //Personne Identite
                        if (!empty($deletedIDs)) {
                            KitPersonneIdentite::deleteAll(['id' => $deletedIDs]);
                        }

                        foreach ($modelsOptionValue as $modelValue) {
                            $modelValue->personne_id = $model->id;
                            if (! ($flag = $modelValue->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }

                        //Telephone
                        if (! empty($deletedPhoneIDs)) {
                            KitTelephone::deleteAll(['id' => $deletedPhoneIDs]);
                        }
                        foreach ($modelsTelephone as $modelValue) {
                            $modelValue->personneId = $model->id;
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
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'modelsOptionValue' => (empty($modelsOptionValue)) ? [new KitPersonneIdentite] : $modelsOptionValue,
            'modelsTelephone' => (empty($modelsTelephone)) ? [new KitTelephone] : $modelsTelephone
        ]);
    }

    /**
     * Deletes an existing KitEmploye model.
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
     * Finds the KitEmploye model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KitEmploye the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KitEmploye::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
