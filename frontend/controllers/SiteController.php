<?php
namespace frontend\controllers;

use common\models\KitEntreprise;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\Cookie;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'index'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    /*[
                        'allow' => false,
                        'roles' => ['index'],
                    ],*/
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new LoginForm();

        if (!Yii::$app->user->isGuest) {
            $enterprise = KitEntreprise::findOne(['user_id' => Yii::$app->user->id]);
            if(is_null($enterprise) || empty($enterprise))
                return $this->redirect(['entreprise/create']);
            else {
                return $this->redirect(['entreprise/update', 'id' => KitEntreprise::findOne(['user_id' => Yii::$app->user->id])->id]);
            }
        }else{
            $this->layout = '@frontend/views/layouts/main_login';
            return $this->render('/site/login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        $this->layout = '@frontend/views/layouts/main_login';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $enterprise = KitEntreprise::find()->where(['user_id' => Yii::$app->user->id])->one();
            if(is_null($enterprise))
                return Yii::$app->response->redirect(['entreprise/create']);
            else
                return $this->redirect(['entreprise/update', 'id' => $enterprise->id]);

            //return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('/site/login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        $this->layout = '@frontend/views/layouts/main_login';

        Yii::$app->user->logout();

        $model = new LoginForm();

        return $this->render('/site/login', [
            'model' => $model,
        ]);
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $this->layout = '@frontend/views/layouts/main_login';

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', Yii::t('app','Thank you for registration. Please check your inbox for verification email.'));
            return $this->refresh();
            //return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $this->layout = '@frontend/views/layouts/main_login';

        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', Yii::t('app','Check your email for further instructions.'));

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app','Sorry, we are unable to reset password for the provided email address.'));
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        $this->layout = '@frontend/views/layouts/main_login';

        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', Yii::t('app','New password saved.'));

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return \yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        $this->layout = '@frontend/views/layouts/main_login';

        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success',  Yii::t('app','Your email has been confirmed and welcome to your personal space. Please fill in your company information.'));
                //return $this->goHome();
                return $this->redirect(Url::toRoute('/entreprise/create'));
            }
        }

        Yii::$app->session->setFlash('error',  Yii::t('app','Sorry, we are unable to verify your account with provided token.'));
        //return $this->goHome();
        return $this->redirect(Url::toRoute('/site/login'));
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $this->layout = '@frontend/views/layouts/main_login';
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', Yii::t('app','Check your email for further instructions.'));
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error',  Yii::t('app','Sorry, we are unable to resend verification email for the provided email address.'));
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    /**
     *
     */
    public function actionLanguage()
    {
        if(isset($_GET['lang'])){
            //var_dump($_GET['lang']);
            Yii::$app->language = $_GET['lang'];
            $cookie = new Cookie([
                'name'=>'lang',
                'value'=>$_GET['lang']
            ]);

            Yii::$app->getResponse()->getCookies()->add($cookie);

            return $this->redirect(Yii::$app->request->referrer);
        }
    }
}
