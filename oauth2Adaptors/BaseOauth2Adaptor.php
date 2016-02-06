<?php
namespace thinker_g\UserAuth\oauth2Adaptors;

use thinker_g\Helpers\controllers\ModelViewController as Controller;
use yii\base\Component;
use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use thinker_g\UserAuth\interfaces\Oauth2Account;
use thinker_g\UserAuth\interfaces\Oauth2Adaptor;

abstract class BaseOauth2Adaptor extends Component implements Oauth2Adaptor
{

    public $id;
    public $clientId;
    public $clientSecret;
    public $apiAuth;
    public $apiAccToken;
    public $apiRes;
    public $callbackRoute = ['oauth2/back'];
    public $authCodeParam = 'code';
    public $csrfParam = 'csrf_token';
    public $scope;
    public $acctModelClass = 'thinker_g\UserAuth\models\ars\UserExtAccount';
    public $userModelClass;

    private $_accessToken;

    /**
     * @inheritdoc
     * @see \thinker_g\UserAuth\interfaces\Oauth2Adaptor::authBack()
     */
    public function authBack(Controller $controller)
    {
        if ($controller->enableCsrfValidation && Yii::$app->getErrorHandler()->exception === null) {
            $request = Yii::$app->request;
            $_POST[$request->methodParam] = 'POST';
            if (!$request->validateCsrfToken($request->get($this->csrfParam))) {
                throw new BadRequestHttpException(Yii::t('yii', 'Unable to verify your data submission.'));
            }
        }
        if (!Yii::$app->request->get($this->authCodeParam)) {
            return $this->handleAuthFailed($controller);
        }
        try {
            $accessToken = $this->requestAccessToken();
            $openUid = $this->fetchOpenUid($accessToken);
        } catch (\Exception $e) {
            return $this->handleServiceFailed($e, $controller);
        }
        if (Yii::$app->user->isGuest) {
            if ($extAcct = call_user_func([$this->acctModelClass, 'findByOpenUid'], $openUid, $this->id)) {
                return $this->handleGuestAcct($extAcct, $controller);
            } else {
                return $this->handleGuestNoAcct($accessToken, $controller);
            }
        } else {
            if ($extAcct = call_user_func([$this->acctModelClass, 'findByOpenUid'], $openUid, $this->id)) {
                return $this->handleUserAcct($extAcct, $controller);
            } else {
                return $this->handleUserNoAcct($accessToken, $openUid, $controller);
            }
        }
    }

    public function getAccessToken($assco = true)
    {
        if (empty($this->_accessToken)) {
            $this->_accessToken = $this->requestAccessToken();
        }
        if (is_array($this->_accessToken) != $assco) {
            $this->_accessToken = $assco ? (array) $this->_accessToken : (object) $this->_accessToken;
        }
        return $this->_accessToken;
    }

    public function loginByOpenUid($openUid, $fromSource = null)
    {
        $fromSource || $fromSource = $this->id;
        $acct = call_user_func([$this->acctModelClass, 'findByOpenUid'], $openUid, $fromSource);
        if ($acct) {
            if (!$this->userModelClass) {
                $this->userModelClass = Yii::$app->user->identityClass;
            }
            $userIdentity = call_user_func([$this->userModelClass, 'findOne'], $acct->getUserId());
            if ($userIdentity) {
                return Yii::$app->user->login($userIdentity);
            }
            return false;
        }
        return false;
    }

    public function bindAccount(Model $user, $oauthAttrs)
    {
        $config = ArrayHelper::merge(['class' => $this->acctModelClass], $oauthAttrs);
        $acct = Yii::createObject($config);
        $acct->setUserId($user->primaryKey);
        return $acct->save(false) ? $acct : false;
    }

    public function createUser($attrs = [])
    {
        if (!$this->userModelClass) {
            $this->userModelClass = Yii::$app->user->identityClass;
        }
        $config = ArrayHelper::merge(['class' => $this->userModelClass], $attrs);
        $user = Yii::createObject($config);
        return $user->save() ? $user : false;
    }

    public function handleServiceFailed(\Exception $e, Controller $controller)
    {
        $controller->view->title = 'Service Failure';
        return $controller->render($controller->viewID, ['message' => 'Please go back and try again.']);
    }

    public function handleAuthFailed(Controller $controller)
    {
        return $controller->render($controller->viewID, ['message' => 'Authentication failed, please try again.']);
    }

    public function handleGuestNoAcct(array $accessToken, Controller $controller)
    {
        return $controller->render($controller->viewID, ['message' => 'Reg new user and bind this Oauth Account']);
    }

    public function handleGuestAcct(Oauth2Account $oauthAcct, Controller $controller)
    {
        $this->loginByOpenUid($oauthAcct->openUid);
        $controller->goBack();
        // return $controller->render($controller->viewID, ['message' => 'Login via Openid.']);
    }

    public function handleUserNoAcct(array $accessToken, $openUid, Controller $controller)
    {
        if (call_user_func_array([$this->acctModelClass, 'findByUserId'], [Yii::$app->user->getId(), $this->id])) {
            return $controller->render($controller->viewID, ['message' => 'Current user has already bound another Oauth2 account']);
        } else {
            return $controller->render($controller->viewID, ['message' => 'Oauth2 account can be bound to current User.']);
        }
    }

    public function handleUserAcct(Oauth2Account $oauthAcct, Controller $controller)
    {
        if ($oauthAcct->getUserId() == Yii::$app->user->getId()) {
            return $controller->render($controller->viewID, ['message' => 'Oauth2 account is already bound to current user.']);
        } else {
            return $controller->render($controller->viewID, ['message' => 'This Oauth2 account has already been bound on another user.']);
        }
    }

    /**
     * @inheritdoc
     * @see \thinker_g\UserAuth\interfaces\Oauth2Adaptor::getAuthUrl()
     */
    abstract public function getAuthUrl($csrfToken = null);

    /**
     * @inheritdoc
     * @param bool $assco Set to true to return an array, false to return an StdObject.
     * @return mixed
     */
    abstract public function requestAccessToken();

    /**
     * @inheritdoc
     * @see \thinker_g\UserAuth\interfaces\Oauth2Adaptor::getOpenUid()
     */
    abstract public function fetchOpenUid($accessToken);

    /**
     * @inheritdoc
     * @see \thinker_g\UserAuth\interfaces\Oauth2Adaptor::fetchResource()
     */
    abstract public function fetchResource($resource, $accessToken, $key = null,  $assco = true);

}

?>

