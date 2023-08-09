<?php

namespace backend\modules\management\controllers;

use budyaga\users\models\forms\SignupForm;
use budyaga\users\models\User;
use common\models\KitTelephone;
use common\models\KitPersonneIdentite;
use common\models\KitVille;
use common\models\UploadForm;
use PhpOffice\PhpSpreadsheet\IOFactory;
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
use yii\web\UploadedFile;

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
                'only' => ['index', 'create', 'view', 'update', 'delete', 'import'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['enterpriseIndex'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['enterpriseCreate'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['enterpriseView'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('enterpriseUpdate', ['user' => $this->findModel(Yii::$app->request->get('id'))]);
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['enterpriseDelete'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['import'],
                        'roles' => ['enterpriseCreate'],
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
        $uploadModel = new UploadForm();
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
                        //Saving Doc ID
                        foreach ($modelsOptionValue as $modelValue) {
                            $modelValue->personne_id = $model->id;
                            $modelValue->personne_type = 'M';
                            $modelValue->create_at = date("Y-m-d H:i:s");
                            if (!($flag = $modelValue->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                        //Saving phone
                        foreach ($modelsTelephone as $modelValue) {
                            $modelValue->personneId = $model->id;
                            $modelValue->personne_type = 'M';
                            $modelValue->create_at = date("Y-m-d H:i:s");
                            if (! ($flag = $modelValue->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                        //Creating account for enterprise
                        $randomPassword = rand(100,9999);
                        $user = new User();
                        $user->username = strtolower($model->raison_sociale);
                        $user->status = User::STATUS_ACTIVE;
                        $user->email = $model->email;
                        $user->sex = 1;
                        $user->setPassword('p@sser'.$randomPassword);
                        $user->generateAuthKey();
                        if (!($flag = $user->save(false))) {
                            $transaction->rollBack();
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

            /*echo "<pre>";
            var_dump($modelsOptionValue);
            echo "</pre>";
            exit;*/
        }

        return $this->render('create', [
            'model' => $model,
            'uploadModel' => $uploadModel,
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

    public function actionImport()
    {
        $model = new KitEntreprise();
        $uploadModel = new UploadForm();
        $modelsOptionValue = [new KitPersonneIdentite];
        $modelsTelephone = [new KitTelephone];

        if (Yii::$app->request->isPost) {
            $uploadModel->file = UploadedFile::getInstance($uploadModel, 'file');

            if ($uploadModel->file && $model->validate(false)) {
                $uploadModel->file->saveAs('uploads/' . $uploadModel->file->baseName . '.' . $uploadModel->file->extension);
                $file = 'uploads/' . $uploadModel->file->baseName . '.' . $uploadModel->file->extension;
                $inputFileType = IOFactory::identify($file);
                $objReader = IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($file);

                $transaction = Yii::$app->db->beginTransaction();
                try{
                    $sheet = $objPHPExcel->getSheet(0);
                    $highestRow = $sheet->getHighestRow();
                    $highestColumn = $sheet->getHighestColumn();

                    $i = 0;
                    for($row=2; $row<=$highestRow; ++$row){
                        $rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row,NULL,TRUE,FALSE);
                        $emptyRow = true;
                        foreach($rowData as $k => $v){
                            if($v){
                                $emptyRow = false;
                            }
                        }
                        if($emptyRow){
                            break;//this can be changed to continute to allow blank row in the excelsheet, otherwise loop will be terminated if blank row is found.
                        }

                        $ville = KitVille::find()->where(['like', 'nom',  '%' . strtoupper(trim($rowData[0][2])) . '%', false])->one();

                        /*
                       //var_dump($ville['id']);
                        //echo '<pre>';
                        var_dump('Entreprise : ' . $rowData[0][0]);
                        echo '<br />';
                        var_dump('Adresse : ' . $rowData[0][1]);
                        echo '<br />';
                        var_dump('Ville : ' . $rowData[0][2]);
                        echo '<br />';
                        var_dump('Civilité : ' . $rowData[0][3]);
                        echo '<br />';
                        var_dump('Pers contact : ' . $rowData[0][4]);
                        echo '<br />';
                        var_dump('Poste  : ' . $rowData[0][5]);
                        echo '<br />';
                        if(!empty($rowData[0][6])) {
                            var_dump('Téléphone 1 : ' . $rowData[0][6]);
                        }
                        echo '<br />';
                        var_dump('Téléphone 2 : ' . $rowData[0][7]);
                        echo '<br />';
                        var_dump('Email : ' . $rowData[0][8]);
                        echo '<br />';
                        //echo '</pre>';
                        echo "-------------------------------------------------<br />";
                        */

                        $enterprise = new KitEntreprise();
                        $enterprise->raison_sociale = $rowData[0][0];
                        $enterprise->sigle = $rowData[0][0];
                        $enterprise->email = $rowData[0][8];
                        $enterprise->adresse_congo = $rowData[0][1];
                        $enterprise->ville_id = $ville['id'];
                        (strtolower(trim($rowData[0][3])) == 'monsieur')? $enterprise->contact_sex = 'M' : $enterprise->contact_sex ='F';
                        $enterprise->contact_name = $rowData[0][4];
                        $enterprise->contact_poste = $rowData[0][5];
                        if (!$enterprise->save()) {
                            $transaction->rollBack();
                        }

                        //Creating account for enterprise
                        $randomPassword = rand(100,9999);
                        $user = new User();
                        $user->username = strtolower($enterprise->raison_sociale);
                        $user->status = User::STATUS_ACTIVE;
                        $user->email = $enterprise->email;
                        $user->sex = 1;
                        $user->setPassword('p@sser'.$randomPassword);
                        $user->generateAuthKey();
                        if (!$user->save(false)) {
                            $transaction->rollBack();
                        }

                        if(!is_null($enterprise->id) || !empty($enterprise->id)){
                            //Telephone 1
                            $telephone_1 = new KitTelephone();
                            $telephone_1->numero = '0'.$rowData[0][6];
                            $telephone_1->personneId = $enterprise->id;
                            $telephone_1->personne_type = 'P';
                            $telephone_1->save();

                            //Telephone 2
                            $telephone_2 = new KitTelephone();
                            $telephone_2->numero = '0'.$rowData[0][7];
                            $telephone_2->personneId = $enterprise->id;
                            $telephone_2->personne_type = 'P';
                            $telephone_2->save();
                            if (!$telephone_2->save()) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    $transaction->commit();

                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data has been loaded successfully'));

                }catch(Exception $e){
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
                unlink($file);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'uploadModel' => $uploadModel,
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
