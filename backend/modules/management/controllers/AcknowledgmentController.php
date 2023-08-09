<?php

namespace backend\modules\management\controllers;

use common\models\KitDemande;
use common\models\KitElementDossierPhysique;
use kartik\mpdf\Pdf;
use Yii;
use common\models\KitAcknowledgment;
use common\models\KitAcknowledgmentSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AcknowledgmentController implements the CRUD actions for KitAcknowledgment model.
 */
class AcknowledgmentController extends Controller
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
                'only' => ['index', 'create', 'view', 'update', 'delete', 'print'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['acknowledgmentIndex'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['acknowledgmentCreate'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['acknowledgmentView'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('acknowledgmentUpdate', ['user' => $this->findModel(Yii::$app->request->get('id'))]);
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['acknowledgmentDelete'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view-pdf'],
                        'roles' => ['acknowledgmentPrint'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all KitAcknowledgment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KitAcknowledgmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single KitAcknowledgment model.
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
     * Creates a new KitAcknowledgment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KitAcknowledgment();

        if ($model->load(Yii::$app->request->post())) {
            $model->code ='ACR/'.KitDemande::findOne(['id'=>$model->demande_id])->code;
            $model->save();
            foreach(Yii::$app->request->post() as $i => $item){
                if($i == 'KitElementDemande'){
                    foreach($item as $data){
                        $modelDossierPhysique = new KitElementDossierPhysique();
                        $modelDossierPhysique->piece_fournie_id = $data['piece_fournir_id'];
                        $modelDossierPhysique->nombre = $data['nombre'];
                        $modelDossierPhysique->acknowledgment_id = $model->id;
                        $modelDossierPhysique->save();
                    }
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing KitAcknowledgment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing KitAcknowledgment model.
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
     * Finds the KitAcknowledgment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KitAcknowledgment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KitAcknowledgment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    protected function generationAcknowledgmentDoc($id){
        $model = $this->findModel($id);
        $logoUrl = 'acpe-logo.png';

        $subject = 'Accusé de réception pour '.strtolower($model->demande->typeAutorisation->libelle);
        $enterprise = $model->demande->employe->entreprise->raison_sociale;
        $employe = $model->demande->employe->nom .' '.$model->demande->employe->prenom;
        $poste = $model->demande->employe->fonction;

        $footer = 'Fait à Brazzaville, le '.Yii::$app->formatter->asDate(time(), 'long');

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('_reportView',[
            'logoUrl'=>$logoUrl,
            'model'=>$model,
            'enterprise'=>$enterprise,
            'subject'=>$subject,
            'employe'=>$employe,
            'poste'=>$poste,
            'footer'=>$footer,
            'pieceFournies'=>$model->kitElementDossierPhysiques,
        ]);

        return $content;
    }

    public function actionViewPdf($id){

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $this->generationAcknowledgmentDoc($id),
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => [
                'title' => 'Krajee Report Title',
                'shrink_tables_to_fit' => 0,
                'useActiveForms'=>true,
            ],
            // call mPDF methods on the fly
            /*'methods' => [
                'SetHeader'=>[$template->name],
                'SetFooter'=>['{PAGENO}'],
            ]*/
        ]);

        return $pdf->render();
    }
}
