<?php

namespace backend\modules\settings\controllers;

use Yii;
use yii\base\Response;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

use common\models\Model;
use common\models\KitTypeAutorisationPiece;
use common\models\KitElementTypeAutorisation;
use common\models\KitTypeAutorisationPieceSearch;
use yii\widgets\ActiveForm;
use common\models\KitTypeAutorisation;


/**
 * TypeAutorisationPieceController implements the CRUD actions for KitTypeAutorisationPiece model.
 */
class TypeAutorisationPieceController extends Controller
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
                        'roles' => ['typeAutotisationPieceIndex'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['typeAutotisationPieceCreate'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['typeAutotisationPieceView'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('typeAutotisationPieceUpdate', ['user' => $this->findModel(Yii::$app->request->get('id'))]);
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['typeAutotisationPieceDelete'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all KitTypeAutorisationPiece models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KitTypeAutorisationPieceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single KitTypeAutorisationPiece model.
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
     * Creates a new KitTypeAutorisationPiece model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KitTypeAutorisationPiece();
        $modelsOptionValue = [new KitElementTypeAutorisation];

        if ($model->load(Yii::$app->request->post())) {
            $modelsOptionValue = Model::createMultiple(KitElementTypeAutorisation::classname());
            Model::loadMultiple($modelsOptionValue, Yii::$app->request->post());

            /*echo "<pre>";
            var_dump($modelsOptionValue);
            echo "</pre>";

            exit;*/

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsOptionValue) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        foreach ($modelsOptionValue as $modelValue) {
                            $modelValue->type_autorisation_piece_id = $model->id;
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
            'modelsOptionValue' => (empty($modelsOptionValue)) ? [new KitElementTypeAutorisation] : $modelsOptionValue
        ]);
    }

    /**
     * Updates an existing KitTypeAutorisationPiece model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelsOptionValue = $model->kitElementTypeAutorisations;

        if ($model->load(Yii::$app->request->post())) {

            $oldIDs = ArrayHelper::map($modelsOptionValue, 'id', 'id');
            $modelsOptionValue = Model::createMultiple(KitElementTypeAutorisation::classname(), [new KitElementTypeAutorisation]);
            Model::loadMultiple($modelsOptionValue, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsOptionValue, 'id', 'id')));

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsOptionValue) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (! empty($deletedIDs)) {
                            KitElementTypeAutorisation::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelsOptionValue as $modelValue) {
                            $modelValue->type_autorisation_piece_id = $model->id;
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
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'modelsOptionValue' => (empty($modelsOptionValue)) ? [new KitElementTypeAutorisation] : $modelsOptionValue
        ]);
    }

    /**
     * Deletes an existing KitTypeAutorisationPiece model.
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
     * Finds the KitTypeAutorisationPiece model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KitTypeAutorisationPiece the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KitTypeAutorisationPiece::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    protected function findModelTypeAutorisation($id)
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
            $model = $this->findModel($id);
            foreach($model->kitElementTypeAutorisations as $key =>$item){
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
}
