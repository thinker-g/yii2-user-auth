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
use thinker_g\Helpers\controllers\ModelViewController;

/**
 * Module splash page controller for backend/frontend of the site.
 * @author Thinker_g
 *
 */
abstract class BaseDefaultController extends ModelViewController
{
    /**
     * @inheritdoc
     * @var string
     */
    public $moduleMvMapAttr = 'mvMap';

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
