<?php

namespace thinker_g\UserAuth\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;

abstract class BaseDefaultController extends Controller
{

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

    public function actionIndex()
    {
        return $this->render('index');
    }

}
