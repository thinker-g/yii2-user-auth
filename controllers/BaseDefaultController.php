<?php
namespace thinker_g\UserAuth\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * Module splash page controller for backend/frontend of the site.
 * @author Thinker_g
 *
 */
abstract class BaseDefaultController extends Controller
{

    /**
     * @inheritdoc
     * @see \yii\base\Component::behaviors()
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => $this->module->roles,
                    ],
                ],
            ],
        ];
    }

    /**
     * Splash page.
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

}
