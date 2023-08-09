<?php
namespace frontend\models;

use Da\QrCode\QrCode;
use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $verifyCode;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('app','This username has already been taken.')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('app','This email address has already been taken.')],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],

            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],



        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => Yii::t('app','Verification Code'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $qrCodeData = $user->username.'-'.$user->email.'-'.Yii::$app->security->generatePasswordHash($this->password);
        // Store the cipher method
        $ciphering = Yii::$app->params['ciphering'];
        // Use OpenSSl Encryption method
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        // Non-NULL Initialization Vector for encryption
        $encryption_iv = Yii::$app->params['encryptionIv'];
        // Store the encryption key
        $encryption_key = Yii::$app->params['encryptionKey'];
        $encryption = openssl_encrypt($qrCodeData, $ciphering, $encryption_key, $options, $encryption_iv);
        $user->qrcode = $encryption;

        /*$qrCode = (new QrCode($encryption))
            ->setSize(250)
            ->setMargin(5)
            ->useForegroundColor(2, 1, 1);
            //->useForegroundColor(11, 119, 189);
        // display directly to the browser
        $user->qrcode = $qrCode->writeString();
        */

        return $user->save() && $this->sendEmail($user);

    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user, $email)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' - Agence Congolaise pour l\'Emploi'])
            ->setTo($email)
            ->setSubject(Yii::t('app','Account registration at ') . Yii::$app->name)
            ->send();
    }


}
