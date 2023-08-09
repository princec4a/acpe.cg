<?php

namespace frontend\controllers;

use common\models\KitTelephone;
use common\models\KitPersonneIdentite;
use Yii;
use common\models\KitEntreprise;
use common\models\KitEntrepriseSearch;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Model;

/**
 * EntrepriseController implements the CRUD actions for KitEntreprise model.
 */
class EntrepriseController extends Controller
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
                        'actions' =>  ['index', 'create', 'view', 'update', 'delete'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all KitEntreprise models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KitEntrepriseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single KitEntreprise model.
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
     * Creates a new KitEntreprise model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KitEntreprise();
        $modelsOptionValue = [new KitPersonneIdentite];
        $modelsTelephone = [new KitTelephone];

        if ($model->load(Yii::$app->request->post())) {
            $modelsOptionValue = Model::createMultiple(KitPersonneIdentite::classname());
            $modelsTelephone = Model::createMultiple(KitTelephone::classname());
            Model::loadMultiple($modelsOptionValue, Yii::$app->request->post());
            Model::loadMultiple($modelsTelephone, Yii::$app->request->post());

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsOptionValue) && Model::validateMultiple($modelsTelephone) && $valid;


            if($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save()) {
                        foreach ($modelsOptionValue as $modelValue) {
                            $modelValue->personne_id = $model->id;
                            $modelValue->personne_type = 'M';
                            if (!($flag = $modelValue->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                        foreach ($modelsTelephone as $modelValue) {
                            $modelValue->personneId = $model->id;
                            $modelValue->personne_type = 'M';
                            if (! ($flag = $modelValue->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['update', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    print_r($e->getMessage());
                    exit;
                }
            }

            /*echo "<pre>";
            var_dump($modelsOptionValue);
            echo "</pre>";
            exit;*/
        }

        return $this->render('create', [
            'model' => $model,
            'modelsOptionValue' => (empty($modelsOptionValue)) ? [new KitPersonneIdentite] : $modelsOptionValue,
            'modelsTelephone' => (empty($modelsTelephone)) ? [new KitTelephone] : $modelsTelephone
        ]);
    }

    /**
     * Updates an existing KitEntreprise model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelsOptionValue = KitEntreprise::getKitPersonneIdentites($id);
        $modelsTelephone = KitEntreprise::getTelephones($id);

        if ($model->load(Yii::$app->request->post())) {
            $oldIDs = ArrayHelper::map($modelsOptionValue, 'id', 'id');
            $modelsOptionValue = Model::createMultiple(KitPersonneIdentite::classname(), $modelsOptionValue);
            Model::loadMultiple($modelsOptionValue, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsOptionValue, 'id', 'id')));

            $oldPhoneIDs = ArrayHelper::map($modelsTelephone, 'id', 'id');
            $modelsTelephone = Model::createMultiple(KitTelephone::classname(), $modelsTelephone);
            Model::loadMultiple($modelsTelephone, Yii::$app->request->post());
            $deletedPhoneIDs = array_diff($oldPhoneIDs, array_filter(ArrayHelper::map($modelsTelephone, 'id', 'id')));


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
                        return $this->redirect(['update', 'id' => $model->id]);
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
     * Deletes an existing KitEntreprise model.
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
     * Finds the KitEntreprise model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KitEntreprise the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KitEntreprise::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
