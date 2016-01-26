<?php
/**
 * @link https://github.com/thinker-g/yii2-user-auth
 * @copyright Copyright (c) Thinker_g
 * @license MIT
 * @author Thinker_g
 * @since v0.1.0
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
class Oauth2Controller extends BaseAuthController
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

    public function actionTryLogin($adaptorId)
    {
        return Html::a('Login from ' . $adaptorId, $this->getAdaptor($adaptorId)->getLoginUrl($this));
    }

    /**
     * Signup action.
     */
    public function actionBack()
    {
        $adaptorId = Yii::$app->request->get($this->module->oauthAdaptorIdParam);
        if (!$adaptorId) {
            throw new BadRequestHttpException('Illegal operation.');
        }
        $this->getAdaptor($adaptorId)->authBack($this);
    }

    public function getAdaptor($adaptorId)
    {
        if ($adaptor = $this->module->getOauthAdaptor($adaptorId)) {
            return $adaptor;
        } else {
            throw new NotFoundHttpException('Adaptor <' . $adaptorId . '> not found.');
        }
    }

}

?>
