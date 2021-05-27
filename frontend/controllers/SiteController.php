<?php

namespace frontend\controllers;

use app\jobs\CreateLogs;
use common\jobs\JobRabbitQueue;
use common\jobs\JobTest;
use common\models\Board;
use common\models\Card;
use common\models\Column;
use common\models\elastic\Board as ElasticBoard;
use common\models\elastic\Card as ElasticCard;
use common\models\elastic\Column as ElasticColumn;
use common\models\Entity;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\UserEntity;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\helpers\VarDumper;

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
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
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

    public function actionTestColumn()
    {
        $title = "backlog";
        $board = Board::findOne(8);
        $newColumn = new Column();
        $newColumn->board_id = $board->id;
        $newColumn->owner_id = Yii::$app->user->id;
        $newColumn->order = 0;
        $newColumn->title = $title;
        $newColumn->save();
        // $columnES = new ElasticColumn();

        echo "<pre>";
        // VarDumper::dump($columnES->searchingAllMatches($title));
        VarDumper::dump($newColumn);
        echo "</pre>";
        die;
    }

    public function actionTestEntity()
    {
        $entity = new Entity();
        $entity->name = 'This is a name';
        $entity->owner_id = Yii::$app->user->id;
        $entity->save();

        echo "<pre>";
        VarDumper::dump($entity);
        echo "</pre>";
        die;
    }

    public function actionDeleteBoard()
    {
        $board = Board::findOne(8);

        if (!$board) {
            die("No board");
        }

        $board->delete();

        echo "<pre>";
        VarDumper::dump($board);
        echo "</pre>";
        die;
    }

    public function actionTestBoard()
    {
        $entity = Entity::findOne(1);
        // $userEntity = new UserEntity();
        // $userEntity->entity_id = $entity->id;
        // $userEntity->user_id = $entity->owner_id;
        // $userEntity->save();

        $title = "last test";
        // $newBoard = new Board();
        // $newBoard->entity_id = $entity->id;
        // $newBoard->owner_id = Yii::$app->user->id;
        // $newBoard->title = $title;
        // $newBoard->save();
        $boardES = new ElasticBoard();

        echo "<pre>";
        VarDumper::dump($boardES->find()->all());
        // VarDumper::dump($newBoard);
        echo "</pre>";
        die;
    }

    public function actionTestCard()
    {
        $title = "elasticsearch";
        // $newCard = new Card();
        // $newCard->column_id = 1;
        // $newCard->owner_id = 1;
        // $newCard->title = $title;
        // $newCard->description = "somethign descript";
        // $newCard->color = "fafafa";
        // $newCard->order = 0;
        // $newCard->save();
        $cardES = new ElasticCard();

        echo "<pre>";
        VarDumper::dump($cardES->searchingAllMatches($title));
        // VarDumper::dump($newCard);
        echo "</pre>";
        die;
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
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
        Yii::$app->user->logout();

        return $this->goHome();
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
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
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
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
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
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

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
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
