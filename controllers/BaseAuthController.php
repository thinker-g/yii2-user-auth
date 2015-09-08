<?php
/**
 * @link https://github.com/thinker-g/yii2-user-auth
 * @copyright Copyright (c) Thinker_g
 * @license MIT
 * @version v0.0.1
 * @author Thinker_g
 * @since v0.0.1
 */

namespace thinker_g\UserAuth\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Base controller class with authentication methods.
 * @author Thinker_g
 * @property \thinker_g\UserAuth\Module $module
 */
abstract class BaseAuthController extends BaseDefaultController
{
    /**
     * @inheritdoc
     * @var string
     */
    public $defaultAction = 'login';

    /**
     * @inheritdoc
     * @var array
     */
    public $controllerMvMap = [
        'login' => ['model' => 'thinker_g\UserAuth\models\forms\LoginForm'],
    ];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
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
     * Login action.
     * @return \yii\web\Response|string
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = Yii::createObject($this->getModelClass());

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render($this->viewID, ['model' => $model]);
        }
    }

    /**
     * Logout action
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}

?>