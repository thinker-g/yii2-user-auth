<?php
namespace thinker_g\UserAuth\controllers;

use yii\filters\AccessControl;
use thinker_g\Helpers\controllers\CrudController;

/**
 * Base controller for managing user account data.
 *
 * @author Thinker_g
 */
abstract class BaseAdminController extends CrudController
{
    /**
     * @inheritdoc
     * @var string
     */
    public $moduleAttr = 'backMvMap';

    /**
     * @inheritdoc
     * @see \thinker_g\Helpers\controllers\CrudController::behaviors()
     */
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

    /**
     * @inheritdoc
     * @see \yii\base\Object::init()
     */
    public function init()
    {
        parent::init();
        $this->getView()->params['sidebarMenu'] = $this->module->baseBackendNavMenu;
    }
}
