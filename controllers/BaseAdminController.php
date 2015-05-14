<?php

namespace thinker_g\UserAuth\controllers;

use yii\filters\AccessControl;
use thinker_g\Helpers\controllers\CrudController;

abstract class BaseAdminController extends CrudController
{
    public $moduleAttr = 'backMvMap';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => $this->module->roles,
                    ],
                ],
            ],
        ];
    }

    public function init()
    {
        parent::init();
        $this->getView()->params['sidebarMenu'] = $this->module->baseBackendNavMenu;
    }
}
