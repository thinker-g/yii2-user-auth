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
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\helpers\Html;
use thinker_g\Helpers\controllers\ModelViewController;

/**
 * Frontend oauth controller.
 *
 * @author Thinker_g
 */
class Oauth2Controller extends ModelViewController
{

    /**
     * @inheritdoc
     * @see \yii\base\Object::init()
     */
    public function init()
    {
         parent::init();
         if (empty($this->module->oauthAdaptors)) {
             throw new NotFoundHttpException('No OAuth adaptor found.');
         }
    }

    /**
     * Display a link points to authorization url
     * @param string $adaptorId
     * @return string
     */
    public function actionTryAuth($adaptorId)
    {
        return Html::a('Authenticate via ' . $adaptorId, $this->getAdaptor($adaptorId)->getAuthUrl($this));
    }

    /**
     * Action returned from authorization url.
     */
    public function actionBack()
    {
        $adaptorId = Yii::$app->request->get($this->module->oauthAdaptorIdParam);
        if (!$adaptorId) {
            throw new BadRequestHttpException('Illegal operation.');
        }
        $this->getAdaptor($adaptorId)->authBack($this);
    }

    /**
     * Return Oauth adaptor.
     * @param string $adaptorId
     * @throws NotFoundHttpException
     * @return \thinker_g\UserAuth\interfaces\Oauth2Adaptor
     */
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