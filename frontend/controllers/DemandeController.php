<?php

namespace frontend\controllers;

use budyaga\users\models\AuthAssignment;
use common\models\KitElementTypeAutorisation;
use common\models\KitLevel;
use common\models\KitRejet;
use common\models\KitUserDepartement;
use common\models\User;
use frontend\components\Notification;
use common\models\KitElementDemande;
use common\models\KitEntreprise;
use common\models\KitDemande;
use common\models\KitDemandeSearch;

use common\models\KitTypeAutorisation;
use kartik\mpdf\Pdf;
use Yii;
use yii\base\Exception;
use common\models\Model;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * DemandeController implements the CRUD actions for KitDemande model.
 */
class DemandeController extends Controller
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
                'only' => ['index', 'create', 'view', 'update', 'delete', 'pdf'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'view', 'update', 'delete', 'pdf'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all KitDemande models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KitDemandeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single KitDemande model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $n = KitDemande::find()->where(['statut'=>$this->getRejectStatus(), 'id' => $id])->count();
        if($n > 0){
            $reject_id = KitRejet::find()->where('demande_id=:demande', [':demande' => $id])->max('id');
            $reject = KitRejet::findOne($reject_id);
            Yii::$app->session->setFlash('error', is_null($reject)? Yii::t('app','Votre demande a été rejetée pour le motif : ') : Yii::t('app','Votre demande a été rejetée pour le motif : ').$reject->motif);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new KitDemande model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KitDemande();
        $elementDemandFiles = new KitElementDemande;
        $modelsElementDemands= [new KitElementDemande];

        if($model->load(Yii::$app->request->post())){
            //check request already exist
            if(!$this->checkRequestExistOrNot($model)){
                $model->date_reception = date('Y-m-d');
                $model->prix = KitTypeAutorisation::find()->where(['id'=>$model->type_autorisation_id])->one()->prix;
                $modelsElementDemands = Model::createMultiple(KitElementDemande::classname());
                Model::loadMultiple($modelsElementDemands, Yii::$app->request->post());
                $elementDemandFiles->files = UploadedFile::getInstances($elementDemandFiles, 'files');

                $data_file = $this->fileToBlob($elementDemandFiles, $modelsElementDemands);

                $valid = $model->validate();
                //$valid = Model::validateMultiple($modelsElementDemands) && $valid;

                if($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        if ($flag = $model->save()) {

                            $model->code = $model->typeAutorisation->code.$model->employe->id.date('YmdHis').'/'.$model->id;
                            $model->update(false,['code']);
                            foreach ($modelsElementDemands as $modelValue) {
                                $modelValue->demande_id = $model->id;
                                //Recuperation des fichiers
                                if (array_key_exists($modelValue->piece_fournir_id, $data_file)) {
                                    $modelValue->file_name = $data_file[$modelValue->piece_fournir_id]['file_name'];
                                    $modelValue->fichier = $data_file[$modelValue->piece_fournir_id]['file_content'];
                                }
                                if(!is_null($modelValue->piece_fournir_id) && !($flag = $modelValue->save(false))) {
                                    break;
                                }
                            }
                        }

                        if ($flag) {
                            //Notifier le verificateur du department correspondant au demandeur
                            $authAssignments = AuthAssignment::find()->where(['item_name'=>'verificateur'])->all();

                            foreach($authAssignments as $data){
                                $user = KitUserDepartement::find()->where(['user_id'=>$data->user_id, 'departement_id'=>$model->employe->lieuTravail->id])->one();
                                if(!is_null($user) || !empty($user))
                                    Notification::success(Notification::KEY_NEW_MESSAGE, $user->user_id, $model->id);
                            }

                            //Notifier le demandeur et son entreprise
                            Yii::$app->session->setFlash('success', Yii::t('app','Your request has been registered and sent to the ACPE. Please check your email for more information.'));
                            $this->sendEmail($model);
                            $transaction->commit();
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    } catch (Exception $e) {
                        var_dump($e->getTraceAsString());
                        Yii::$app->session->setFlash('error', Yii::t('app','An error occurred while saving the request, Please contact the administrator'));
                        $transaction->rollBack();
                    }
                }
            }else{
                Yii::$app->session->setFlash('error', Yii::t('app','There is already a request: "{name}" still valid or being processed', ['name'=>KitTypeAutorisation::findOne($model->type_autorisation_id)->code]));
            }

        }

        return $this->render('create', [
            'model' => $model,
            'modelsElementDemands' => $modelsElementDemands,
            'modelTypeAutorisationPiece' => new KitElementTypeAutorisation(),
        ]);
    }

    /**
     * Updates an existing KitDemande model.
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
     * Deletes an existing KitDemande model.
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
     * Finds the KitDemande model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KitDemande the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KitDemande::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReport($id) {
        $model = $this->findModel($id);
        //$logoUrl = 'http://acpe.afro10.fr/acpe_ftp/common/logo/acpe.png';
        $logoUrl = 'http://localhost:90/acpe.cg/common/logo/acpe.png';
        $niu = '';
        $rccm= '';
        $telephones = '';
        $ville = KitEntreprise::getVille($model->employe->entreprise->ville_id)->nom;
        foreach(KitEntreprise::getKitPersonneIdentites($model->employe->entreprise_id) as $key=>$personId){
            if($personId->typePiece->code == 'NIU') $niu = $personId->numero;
            if($personId->typePiece->code == 'RCCM') $rccm = $personId->numero;
        }
        foreach(KitEntreprise::getTelephones($model->employe->entreprise_id) as $key=>$telephone ){
            $telephones .= ' - '.$telephone->numero;
            if($key==1)  break;
        }

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('_reportView',[
            'logo'=>$logoUrl,
            'model'=>$model,
            'niu'=>$niu,
            'rccm'=>$rccm,
            'ville'=>$ville,
            'telephones'=>$telephones,
        ]);

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
            'content' => $content,
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
            'methods' => [
                'SetHeader'=>['Formulaire '.$model->typeAutorisation->code],
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        return $pdf->render();
    }

    /**
     *
     */
    public function fileToBlob($elementDemandFiles, $modelsElementDemands){
        $data_file = $array_file = $array = [];
        foreach ($elementDemandFiles->files as $file) {
            $array_file[$file->baseName . '.' . $file->extension] = file_get_contents($file->tempName);
        }

        foreach($modelsElementDemands as $item){
            if(!is_null($item->files_id) || !empty($item->files_id)){
                $array[$item->files_id] = $item->files_name;
            }
        }

        foreach($array as $k => $file){
            foreach($array_file as  $key => $file_content){
                if(str_contains($file, $key)){
                    $data_file[$k]['file_name'] = $key;
                    $data_file[$k]['file_content'] = $file_content;
                }
            }
        }

        /*echo '<pre>';
        var_dump($data_file);
        echo '</pre>';
        exit;*/

        return $data_file;
    }

    public function actionPdf($id) {
        $model = KitElementDemande::find()->where(['piece_fournir_id'=>$id])->one();

        return Yii::$app->response->sendContentAsFile(
            $model->fichier,
            $model->file_name,
            ['inline' => true, 'mimeType' => 'application/pdf']
        );
    }

    public function getRejectStatus(){
        $reject = [];
        foreach(KitLevel::find()->all() as $level)
            array_push($reject, $level->level_number * 10);

        return $reject;
    }

    public function getValidateStatus(){
        $validate = [];
        foreach(KitLevel::find()->all() as $level)
            array_push($validate, $level->level_number);

        return $validate;
    }

    /**
     * Sends confirmation email to user
     * @param KitDemande $model
     * @return bool whether the email was sent
     */
    protected function sendEmail($model)
    {
        Yii::$app
            ->mailer
            ->compose(
                ['html' => 'requestForwarded-html', 'text' => 'requestForwarded-text'],
                ['model' => $model]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' - Agence Congolaise pour l\'Emploi'])
            ->setTo([$model->employe->entreprise->user->email, $model->employe->email])
            ->setSubject(Yii::t('app','Receipt of your request for ') . $model->typeAutorisation->libelle . ' ' . Yii::t('app','by ') . Yii::$app->name)
            ->send();
    }

    /**
     * Check request already exist
     * @param KitDemande $model
     * @return bool whether the email was sent
     */
    private function checkRequestExistOrNot($model){
        $id = KitDemande::find()
                ->where(['employe_id'=>$model->employe_id,'type_autorisation_id'=>$model->type_autorisation_id])
                ->max('id');
        if(is_null($id))
            return false;
        else{
            $expiration_date = KitDemande::findOne($id)->expiration_date;
            if(is_null($expiration_date))
                return true;
            elseif($expiration_date < time())
                return true;
            else
                return false;
        }
    }

}
