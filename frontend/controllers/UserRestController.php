<?php
namespace frontend\controllers;

use common\models\User;
use frontend\models\SignupForm;
use yii\helpers\Json;
use yii\rest\ActiveController;
use yii\web\Response;

/**
 * Class PersonController
 *
 * @author Prince TSATY
 * @package frontend\controllers
 */
class UserRestController extends ActiveController
{

    public $modelClass = SignupForm::class;

    /**
     * Signs user up.
     *
     * @return mixed
     *
     * */
    public function actionSignup()
    {

        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new SignupForm();
        $model->attributes = \Yii::$app->request->post();

        var_dump($model);
        /*
        */
        /*
        \Yii::$app->session->setFlash('success', \Yii::t('app','Thank you for registration. Please check your inbox for verification email.'));
        $model = new SignupForm();
        if ($model->load(\Yii::$app->request->post()) && $model->signup()) {
            //\Yii::$app->session->setFlash('success', \Yii::t('app','Thank you for registration. Please check your inbox for verification email.'));
            //return $this->refresh();
        }
        */
    }

    public function actionLogin(){

    }

    public function actionLoginQrcode($encryption){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $options = 0;
        $ciphering = \Yii::$app->params['ciphering'];
        $decryptionIv = \Yii::$app->params['decryptionIv'];
        $decryptionKey = \Yii::$app->params['decryptionKey'];

        $qrcode = openssl_decrypt($encryption, $ciphering, $decryptionKey, $options, $decryptionIv);
        $array = explode('-',$qrcode);

        $user = User::find()->where('username =:username and status=:status', [':username'=>$array[0], ':status'=>10])->one();

        if(is_null($user)) return false;
        else return true;
    }

    public function actionValidatePassword($encryption, $password_encrypt){
        $options = 0;
        $ciphering = \Yii::$app->params['ciphering'];
        $decryptionIv = \Yii::$app->params['decryptionIv'];
        $decryptionKey = \Yii::$app->params['decryptionKey'];

        $qrcode = openssl_decrypt($encryption, $ciphering, $decryptionKey, $options, $decryptionIv);
        $password = openssl_decrypt($password_encrypt, $ciphering, $decryptionKey, $options, $decryptionIv);
        $array = explode('-',$qrcode);

        //var_dump($array[0]);
        //var_dump($password);

        $user = User::find()->where('username =:username and status=:status', [':username'=>$array[0], ':status'=>10])->one();

        if($this->validatePassword($password, $user->password_hash))
            return Json::encode($user);
        else
            return null;
    }

    public function validatePassword($password, $password_hash)
    {
        return \Yii::$app->security->validatePassword($password, $password_hash);
    }

    public function actionCrypt($password){
        $options = 0;
        $ciphering = \Yii::$app->params['ciphering'];
        $encryption_iv = \Yii::$app->params['decryptionIv'];
        $encryption_key = \Yii::$app->params['decryptionKey'];
        $encryption = openssl_encrypt($password, $ciphering, $encryption_key, $options, $encryption_iv);
        return $encryption;
    }


}