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
    public $moduleMvMapAttr = 'backMvMap';

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
}
