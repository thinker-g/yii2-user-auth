<?php
/**
 * @link https://github.com/thinker-g/yii2-user-auth
 * @copyright Copyright (c) Thinker_g
 * @license MIT
 * @version v0.0.1
 * @author Thinker_g
 * @since v0.0.1
 */

namespace thinker_g\UserAuth\controllers\front;

use Yii;
use thinker_g\UserAuth\controllers\BaseAuthController;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\helpers\Html;

/**
 * Frontend auth controller, provides in addition the password reset actions.
 *
 * @author Thinker_g
 */
class OauthController extends BaseAuthController
{

    /**
     * @inheritdoc
     * @see \yii\base\Object::init()
     */
    public function init()
    {
         parent::init();
         if (empty($this->module->oauthAdaptors)) {
             throw new NotFoundHttpException('Page not found.');
         }
    }

    public function actionTryAdaptor($adaptorId = null)
    {
        return Html::a('Login from ' . $adaptorId, $this->getAdaptor($adaptorId)->getLoginUrl($this));
    }

    /**
     * Signup action.
     */
    public function actionBack()
    {
        //TODO: remove this fixed key "from_source"
        $adaptor = $this->getAdaptor(Yii::$app->request->get('from_source'));
        $adaptor->authBack($this);
    }

    public function getAdaptor($adaptorId)
    {
        return $this->module->getOauthAdaptor($adaptorId);
    }

    public function actionEcho()
    {
        var_dump($_GET, $_POST);
    }
}

?>