<?php
namespace thinker_g\UserAuth\controllers;

use Yii;
use thinker_g\Helpers\controllers\CrudController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Base controller class with authentication methods.
 * @author Thinker_g
 * @property \thinker_g\UserAuth\Module $module
 */
abstract class BaseAuthController extends CrudController
{
    public $moduleAttr = 'mvMap';

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

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = Yii::createObject($this->module->modelLoginForm);
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render($this->viewID, [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}

?>