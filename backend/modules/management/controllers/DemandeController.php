<?php

namespace backend\modules\management\controllers;

use budyaga\users\models\AuthAssignment;
use budyaga\users\models\User;
use common\models\KitCircuitValidation;
use common\models\KitElementTypeAutorisation;
use backend\components\Notification;
use common\models\KitElementDemande;
use common\models\KitEntreprise;
use common\models\KitDemande;
use common\models\KitDemandeSearch;

use common\models\KitLevel;
use common\models\KitRejet;
use common\models\KitReport;
use common\models\KitTag;
use common\models\KitTypeAutorisation;
use common\models\KitUserDepartement;
use common\models\KitValidationDemande;
use common\models\UploadForm;
use common\models\UploadRejectRequestForm;
use common\models\UploadRequestForm;
use kartik\mpdf\Pdf;
use PhpOffice\PhpSpreadsheet\Shared\StringHelper;
use Yii;
use yii\base\Exception;
use common\models\Model;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
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
                'only' => ['index', 'create', 'view', 'update', 'delete', 'pdf','import', 'view-pdf', 'import-reject'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'view', 'update', 'delete', 'pdf','import','view-pdf', 'import-reject'],
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
        $uploadModel = new UploadRequestForm();
        $uploadRejectModel = new UploadRejectRequestForm();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'uploadModel' => $uploadModel,
            'uploadRejectModel'=>$uploadRejectModel
        ]);
    }

    public function actionImport()
    {
        $uploadModel = new UploadRequestForm();

        if (Yii::$app->request->isPost) {
            $uploadModel->load(Yii::$app->request->post());
            $model = $this->findModel($uploadModel->id);
            $uploadModel->file = UploadedFile::getInstance($uploadModel, 'file');
            $uploadModel->fileLetter = UploadedFile::getInstance($uploadModel, 'fileLetter');
            $model->file = file_get_contents($uploadModel->file->tempName);
            $model->file_name = $uploadModel->file->baseName . '.' . $uploadModel->file->extension;
            $model->signed_date = time();
            if($model->typeAutorisation->type_duree == 'JOUR')
                $model->expiration_date = strtotime('+'.$model->typeAutorisation->duree_validite.' days', $model->signed_date);
            if($model->typeAutorisation->type_duree == 'SEMAINE')
                $model->expiration_date = strtotime('+'.($model->typeAutorisation->duree_validite*7).' days', $model->signed_date);
            if($model->typeAutorisation->type_duree == 'MOIS')
                $model->expiration_date = strtotime('+'.$model->typeAutorisation->duree_validite.' months', $model->signed_date);
            if($model->typeAutorisation->type_duree == 'ANNEE')
                $model->expiration_date = strtotime('+'.$model->typeAutorisation->duree_validite.' years', $model->signed_date);
            $model->save(false);
            // Save validation letter
            $letter = KitValidationDemande::find()->where(['demande_id'=>$uploadModel->id, 'level'=>$uploadModel->status])->one();
            $letter->file = file_get_contents($uploadModel->fileLetter->tempName);
            $letter->file_name = $uploadModel->fileLetter->baseName . '.' . $uploadModel->fileLetter->extension;
            $letter->save(false);
            //send mail to enterprise and employee
            $this->sendEmail($model, $letter);
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Document successfully signed'));
            return $this->redirect(['view','id' => $model->id]);
        }
    }

    public function actionImportReject()
    {
        $uploadModel = new UploadRejectRequestForm();

        if (Yii::$app->request->isPost) {
            $uploadModel->load(Yii::$app->request->post());
            $model = $this->findModel($uploadModel->id);
            $uploadModel->fileLetter = UploadedFile::getInstance($uploadModel, 'fileLetter');
            // Save rejection letter
            $letter = KitRejet::find()->where(['demande_id'=>$uploadModel->id, 'level'=>$uploadModel->status])->one();
            $letter->file = file_get_contents($uploadModel->fileLetter->tempName);
            $letter->file_name = $uploadModel->fileLetter->baseName . '.' . $uploadModel->fileLetter->extension;
            $letter->save(false);
            //send mail to enterprise and employee
            $this->sendRejectEmail($model, $letter);
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Letter successfully sent'));
            return $this->redirect(['view','id' => $model->id]);
        }
    }

    /*protected function importLetter()
    {
        $uploadModel = new UploadRequestForm();

        if (Yii::$app->request->isPost) {
            $uploadModel->load(Yii::$app->request->post());

            $letter = ($uploadModel->status % 10 == 0)? KitRejet::find()->where(['id'=>$uploadModel->id, 'level'=>$uploadModel->status])->one()
                : KitValidationDemande::find()->where(['id'=>$uploadModel->id, 'level'=>$uploadModel->status])->one();
            $uploadModel->fileLetter = UploadedFile::getInstance($uploadModel, 'fileLetter');
            $letter->file = file_get_contents($uploadModel->file->tempName);
            $letter->file_name = $uploadModel->file->baseName . '.' . $uploadModel->file->extension;
            $letter->save(false);
            //send mail to enterprise and employee
            $this->sendEmail($letter);
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Document successfully signed'));
            return $this->redirect(['view','id' => $model->id]);
        }
    }*/

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
                            $transaction->commit();
                            //Notifier le verificateur
                            $user = AuthAssignment::find()->where(['item_name'=>'verificateur'])->one();
                            Notification::success(Notification::KEY_NEW_MESSAGE, $user->user_id, $model->id);
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', $e->getMessage());
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

    /*
     * a décommenter tout à l'heure
     *
     */
    public function actionReport($id) {
        $model = $this->findModel($id);
        //$logoUrl = 'http://localhost:90/acpe.cg/common/logo/acpe.png';
        $logoUrl = 'acpe-logo.png';
        $niu = '';
        $rccm= '';
        $telephones = '';
        //$signature = (Yii::$app->user->can('userSigned', ['user' => $model]))? User::findOne(['id'=>Yii::$app->user])->signature : '';

        $ville = KitEntreprise::getVille($model->employe->entreprise->ville_id)->nom;
        $niu = $model->employe->entreprise->niu;
        foreach(KitEntreprise::getKitPersonneIdentites($model->employe->entreprise_id) as $personId){
            $rccm = $personId->numero;
        }
        foreach(KitEntreprise::getTelephones($model->employe->entreprise_id) as $key=>$telephone ){
            $telephones .= ' - '.$telephone->numero;
            if($key==1)  break;
        }

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('_reportView',[
            'logoUrl'=>$logoUrl,
            'model'=>$model,
            'niu'=>$niu,
            'rccm'=>$rccm,
            'ville'=>$ville,
            'telephones'=>$telephones,
            //'signature'=>$signature,
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
        //return $content;
    }

    /**
     * Reject KitDemande model.
     * If reject is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function  actionReject(){
        $reject = new KitRejet();
        $authAssignement = ArrayHelper::map(AuthAssignment::find()->where(['user_id'=>Yii::$app->user->id])->all(), 'item_name','user_id');

        if($reject->load(Yii::$app->request->post())){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $reject->save();
                $model = $this->findModel($reject->demande_id);
                //foreach(KitLevel::find()->all() as $level){
                foreach($model->typeAutorisation->kitCircuitValidations as $circuit){
                    if(array_key_exists(ucwords(strtolower($circuit->level->level_name)), $authAssignement)){
                        $reject->level = $circuit->level->status_code * 10;
                        $reject->save(false);
                        $model->statut = $circuit->level->status_code * 10;
                        $model->save(false);
                        $next = $circuit->level->level_number + 1;
                        $nextLevel = KitLevel::find()->where(['level_number'=>$next])->one();
                        //$userNextLevel = AuthAssignment::find()->where(['item_name'=>ucwords(strtolower($nextLevel->level_name))])->one();
                        $authAssignments = AuthAssignment::find()->where(['item_name'=>ucwords(strtolower($nextLevel->level_name))])->all();
                        foreach($authAssignments as $data){
                            $user = KitUserDepartement::find()->where(['user_id'=>$data->user_id, 'departement_id'=>$model->employe->lieuTravail->id])->one();
                            if(!is_null($user) || !empty($user))
                                Notification::success(Notification::KEY_NEW_MESSAGE, $user->user_id, $model->id);
                        }
                        //Notify next user level
                        //Notification::success(Notification::KEY_NEW_MESSAGE, $userNextLevel->user_id, $model->id);
                        //Notify account of employee
                        if(!is_null($model->employe->entreprise->user)){
                            Notification::success(Notification::KEY_NEW_MESSAGE, $model->employe->entreprise->user->id, $model->id);
                        }
                        //Generate validate or reject file in max level
                        if($nextLevel == KitCircuitValidation::getMaxLevel($model->type_autorisation_id)){
                            $this->generationRejectLetter($model->id, $model->statut);
                        }
                        $transaction->commit();
                        break;
                    }
                }
            }catch (Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Dossier rejeté avec succès!'));
        return $this->redirect(['view', 'id' => $reject->demande_id]);
    }

    /**
     * Validate KitDemande model.
     * If validate is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function  actionValidate(){
        $validation = new KitValidationDemande();
        $authAssignement = ArrayHelper::map(AuthAssignment::find()->where(['user_id'=>Yii::$app->user->id])->all(), 'item_name','user_id');

        if($validation->load(Yii::$app->request->post())){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                //$validation->save();
                $model = $this->findModel($validation->demande_id);
                //foreach(KitLevel::find()->all() as $level){
                foreach($model->typeAutorisation->kitCircuitValidations as $circuit){
                    if(array_key_exists(ucwords(strtolower($circuit->level->level_name)), $authAssignement)){
                        $validation->level = $circuit->level->status_code;
                        $validation->save(false);
                        $model->statut = $circuit->level->status_code;
                        $model->save(false);
                        $next = $circuit->level->level_number + 1;
                        $nextLevel = KitLevel::find()->where(['level_number'=>$next])->one();
                        //$userNextLevel = AuthAssignment::find()->where(['item_name'=>ucwords(strtolower($nextLevel->level_name))])->one();
                        $authAssignments = AuthAssignment::find()->where(['item_name'=>ucwords(strtolower($nextLevel->level_name))])->all();
                        foreach($authAssignments as $data){
                            $user = KitUserDepartement::find()->where(['user_id'=>$data->user_id, 'departement_id'=>$model->employe->lieuTravail->id])->one();
                            if(!is_null($user) || !empty($user))
                                Notification::success(Notification::KEY_NEW_MESSAGE, $user->user_id, $model->id);
                        }
                        //Notify next user level
                        //Notification::success(Notification::KEY_NEW_MESSAGE, $userNextLevel->user_id, $model->id);
                        //Notify account of employee
                        //Notification::success(Notification::KEY_NEW_MESSAGE, $model->employe->entreprise->user->id, $model->id);

                        //Generate validate or reject file in max level
                        if($nextLevel == KitCircuitValidation::getMaxLevel($model->type_autorisation_id)){
                            $this->generationValidateLetter($model->id, $model->statut);
                        }

                        $transaction->commit();
                        break;
                    }
                }
            }catch (Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }

        }

        Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Dossier validé !'));
        return $this->redirect(['view', 'id' => $validation->demande_id]);
    }

    /**
     *
     */
    public function actionPdf($id) {
        $model = KitElementDemande::find()->where(['piece_fournir_id'=>$id])->one();

        return Yii::$app->response->sendContentAsFile(
            $model->fichier,
            $model->file_name,
            ['inline' => true, 'mimeType' => 'application/pdf']
        );
    }

    /**
     *
     */
    public function actionViewPdf($id) {

        $model = $this->findModel($id);

        return Yii::$app->response->sendContentAsFile(
            $model->file,
            $model->file_name,
            ['inline' => true, 'mimeType' => 'application/pdf']
        );
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

        return $data_file;
    }


    /**
     * Sends confirmation email to user
     * @param KitDemande $model
     * @param KitValidationDemande $letter
     * @return bool whether the email was sent
     */
    protected function sendEmail($model, $letter)
    {
        $filename = yii::$app->basePath.'/'.$model->file_name; //temporary file name
        $fileLetterName = yii::$app->basePath.'/'.$letter->file_name; //temporary file name
        file_put_contents($filename,$model->file);
        file_put_contents($fileLetterName,$letter->file);
        chmod($filename,0770);
        chmod($fileLetterName,0770);
        Yii::$app
            ->mailer
            ->compose(
                ['html' => 'requestValidate-html', 'text' => 'requestValidate-text'],
                ['model' => $model]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' - Agence Congolaise pour l\'Emploi'])
            ->setTo($model->employe->entreprise->email)
            ->setSubject(Yii::t('app','Delivery of your ') . $model->typeAutorisation->libelle . ' ' . Yii::t('app','by ') . Yii::$app->name)
            //->attach(\Swift_Attachment::fromPath($filename))
            ->attach($filename, ['fileName' => $model->file_name, 'contentType' => 'application/x-pdf'])
            ->attach($fileLetterName, ['fileName' => $letter->file_name, 'contentType' => 'application/x-pdf'])
            ->send();

        unlink($filename);
        unlink($fileLetterName);
    }

    /**
     * Sends confirmation email to user
     * @param KitDemande $model
     * @param KitRejet $letter
     * @return bool whether the email was sent
     */
    protected function sendRejectEmail($model, $letter)
    {
        $fileLetterName = yii::$app->basePath.'/'.$letter->file_name; //temporary file name
        file_put_contents($fileLetterName,$letter->file);
        chmod($fileLetterName,0770);
        Yii::$app
            ->mailer
            ->compose(
                ['html' => 'requestReject-html', 'text' => 'requestReject-text'],
                ['model' => $model]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' - Agence Congolaise pour l\'Emploi'])
            ->setTo($model->employe->entreprise->email)
            ->setSubject(Yii::t('app','Delivery of your ') . $model->typeAutorisation->libelle . ' ' . Yii::t('app','by ') . Yii::$app->name)
            ->attach($fileLetterName, ['fileName' => $letter->file_name, 'contentType' => 'application/x-pdf'])
            ->send();

        unlink($fileLetterName);
    }

    /*
    protected function generationLetter($id, $status){
        $model = $this->findModel($id);
        $logoUrl = 'acpe-logo.png';

        $ville = KitEntreprise::getVille($model->employe->entreprise->ville_id)->nom;
        $template = KitReport::find()->where(['action'=>$status])->one();
        //$validation = KitValidationDemande::find()->where(['demande_id'=>$model->id])->one();
        //$rejection = KitRejet::find()->where(['demande_id'=>$model->id])->one();
        $fullName = $model->employe->nom .' '.$model->employe->prenom;
        //Last validation date
        //$validateDate = Yii::$app->formatter->asDate($validation->created_at);
        $validateDate = Yii::$app->formatter->asDate(time(), 'long');
        //Last rejection date
        //$rejectDate = Yii::$app->formatter->asDate($rejection->created_at);
        $rejectDate = Yii::$app->formatter->asDate(time(), 'long');
        $arrayTag = ArrayHelper::map(KitTag::find()->orderBy('id')->all(), 'id','tag');
        $arrayReplaceValue = [
            Yii::$app->formatter->asDate(time(), 'long'),
            $model->typeAutorisation->libelle,
            $fullName,
            ($model->statut == $status && ($status%10) == 0)? $rejectDate : $validateDate,
            $model->employe->entreprise->raison_sociale
        ];

        $header = str_replace($arrayTag, $arrayReplaceValue, $template->header);
        $body = str_replace($arrayTag, $arrayReplaceValue, $template->body);
        $footer = str_replace($arrayTag, $arrayReplaceValue, $template->footer);

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('_reportRender',[
            'logoUrl'=>$logoUrl,
            'model'=>$model,
            'ville'=>$ville,
            'template'=>$template,
            'header'=>$header,
            'body'=>$body,
            'footer'=>$footer,
        ]);

        return $content;
    }*/

    protected function generationValidateLetter($id, $status){
        $model = $this->findModel($id);
        $logoUrl = 'acpe-logo.png';

        $ville = KitEntreprise::getVille($model->employe->entreprise->ville_id)->nom;
        $validation = KitValidationDemande::find()->where(['demande_id'=>$model->id, 'level'=>$status])->one();
        $fullName = $model->employe->nom .' '.$model->employe->prenom;
        //Last validation date
        $validateDate = Yii::$app->formatter->asDate($validation->created_at, 'long');
        //$validateDate = Yii::$app->formatter->asDate(time(), 'long');

        $header = 'Brazzaville, le '.Yii::$app->formatter->asDate(time(), 'long');
        $sender = 'Le Directeur Général de l’Agence Congolaise Pour l’Emploi (ACPE)';
        $receiver = 'A<br/>Monsieur le Directeur Général de la Société <strong>'
            .$model->employe->entreprise->raison_sociale
            .'</strong><br/><strong>'.$ville.'</strong>';
        $subject = (str_starts_with($model->typeAutorisation->libelle, 'L\''))? 'Validation de '.strtolower($model->typeAutorisation->libelle)
            : 'Validation du '.strtolower($model->typeAutorisation->libelle);
        $headerContent = 'Monsieur le Directeur Général,';
        $bodyContent = 'J’ai le plaisir de vous annoncer que <strong>'
            .$model->typeAutorisation->libelle
            .' ('.$model->typeAutorisation->code.')</strong>  de votre employé <strong>'
            .$fullName.'</strong> est validée en date du <strong>'.$validateDate.'</strong>.';
        $greeting = 'Veuillez agréer, Monsieur le Directeur Général, l’expression de ma profonde considération';
        $footer = 'Le Directeur Général';
        $director = 'Wilfrid BITSY';

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('_reportValidate',[
            'logoUrl'=>$logoUrl,
            'model'=>$model,
            'ville'=>$ville,
            'header'=>$header,
            'sender'=>$sender,
            'receiver'=>$receiver,
            'subject'=>$subject,
            'headerContent'=>$headerContent,
            'bodyContent'=>$bodyContent,
            'greeting'=>$greeting,
            'footer'=>$footer,
            'director'=>$director,
        ]);

        return $content;
    }

    protected function generationRejectLetter($id, $status){
        $model = $this->findModel($id);
        $logoUrl = 'acpe-logo.png';

        $civility = $model->employe->sexe == 'M' ? 'Monsieur' : 'Madame';

        $ville = KitEntreprise::getVille($model->employe->entreprise->ville_id)->nom;
        $rejection = KitRejet::find()->where(['demande_id'=>$model->id, 'level'=>$status])->one();
        $fullName = $model->employe->nom .' '.$model->employe->prenom;
        //Last reject by status and id request
        $rejectDate = Yii::$app->formatter->asDate($rejection->created_at, 'long');

        $header = 'Brazzaville, le '.Yii::$app->formatter->asDate(time(), 'long');
        $sender = 'Le Directeur Général de l’Agence Congolaise Pour l’Emploi (ACPE)';
        //$receiver = 'Au<br/>Chef d’Agence de  l’ACPE <stron><br/><stron>'.$ville.'</stron>';
        $receiver = 'A<br/>Monsieur le Directeur Général de la Société <strong>'
            .$model->employe->entreprise->raison_sociale.'</strong>';
        $subject = (str_starts_with($model->typeAutorisation->libelle, 'L\''))? 'Rejet de '.strtolower($model->typeAutorisation->libelle)
            : 'Rejet du '.strtolower($model->typeAutorisation->libelle);
        $headerContent = 'Monsieur le Directeur Général,';
        $bodyContent = 'J’ai le regret de vous renvoyer non visé, l\'autorisation suivante : <strong>'
            .$model->typeAutorisation->libelle
            .' ('.$model->typeAutorisation->code.')</strong>  de '.$civility.' <strong>'
            .$fullName.'</strong> de la société <strong>'.$model->employe->entreprise->raison_sociale.'</strong> pour les motifs répertoriés dans le tableau ci-dessous : ';
        $greeting = 'Je vous prie d\'agréer, Monsieur le Directeur Général, l\'expretion de mes sentiments distingués';
        $footer = 'Le Directeur Général';
        $director = 'Wilfrid BITSY';

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('_reportReject',[
            'logoUrl'=>$logoUrl,
            'model'=>$model,
            'ville'=>$ville,
            'header'=>$header,
            'sender'=>$sender,
            'receiver'=>$receiver,
            'subject'=>$subject,
            'headerContent'=>$headerContent,
            'bodyContent'=>$bodyContent,
            'greeting'=>$greeting,
            'motif'=>$rejection->motif,
            'footer'=>$footer,
            'director'=>$director,
        ]);

        return $content;
    }

    public function actionValidateLetter($id, $status){

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
            'content' => $this->generationValidateLetter($id, $status),
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

    public function actionRejectLetter($id, $status){

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
            'content' => $this->generationRejectLetter($id, $status),
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
