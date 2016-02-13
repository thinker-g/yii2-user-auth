<?php
/**
 * Base class of an oauth2 adaptor of the UserAuth module.
 *
 * By extending this class you just need to implement the four abstract methods, which are highly service depended,
 * to integrate a new oauth2 service provider.
 * For authenticating a user, main logics are already implemented.
 *
 * For signing-up or signing-in users, you may need to override some of the methods prefixed by "handle".
 * By default, the "handle" methods only display a message that indicates the process you may expect.
 * The contents returned by a handle method will be directly returned by the "auth back" action,
 * (which in most time is the rendered page).
 *
 * You can still create your own adaptor. You just need to implement the interface `thinker_g\UserAuth\interfaces\Oauth2Adaptor`.
 *
 * @version v0.1.0
 * @since v0.1.0
 * @copyright Thinker_g
 * @license MIT
 */
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

    /**
     * ID of this adaptor.
     * @var string
     */
    public $id;

    /**
     * Oauth2 client id.
     * @var string
     */
    public $clientId;

    /**
     * Oauth2 client secret.
     * @var string
     */
    public $clientSecret;

    /**
     * The authenticate api.
     * @var string
     */
    public $apiAuth;

    /**
     * The api to request access token.
     * @var string
     */
    public $apiAccToken;

    /**
     * The api to aquire resources.
     * @var string
     */
    public $apiRes;

    /**
     * The callback route that will be transformed to absolute url and send to auth api.
     * @var string
     */
    public $callbackRoute = ['oauth2/back'];

    /**
     * The parameter name of authorization code returned from auth url.
     * @var sting
     */
    public $authCodeParam = 'code';

    /**
     * The parameter name for CSRF validation, this will be added to auth url and the value will be send by as it is,
     * for CSRF validation.
     * @var string
     */
    public $csrfParam = 'csrf_token';

    /**
     * Authorization scope, value depends on the oauth service requirement.
     * @var string
     */
    public $scope;

    /**
     * The external user account class.
     * The class must be a Model and implement interface `thinker_g\UserAuth\interfaces\Oauth2Account`.
     * @var string
     */
    public $acctModelClass = 'thinker_g\UserAuth\models\ars\UserExtAccount';

    /**
     * The user identity class.
     * The class must be a Model and implement interface `yii\web\IdentityInterface`.
     * @var unknown
     */
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
            $accessToken = $this->getAccessToken();
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
        $this->loginByOpenUid($oauthAcct->getOpenUid());
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
     * The method that actually send the request to get the access token.
     * The return must be an array as ['token' => TOKEN, 'expiresAt' => TIMESTAMP],
     * where the "TOKEN" is the aquired access token,
     * and the TIMESTAMP is an unix timestamp indicates the time this token expires.
     * @return array
     */
    abstract public function requestAccessToken();

    /**
     * @inheritdoc
     * @see \thinker_g\UserAuth\interfaces\Oauth2Adaptor::fetchOpenUid()
     */
    abstract public function fetchOpenUid($accessToken);

    /**
     * @inheritdoc
     * @see \thinker_g\UserAuth\interfaces\Oauth2Adaptor::fetchResource()
     */
    abstract public function fetchResource($resource, $accessToken, $key = null,  $assco = true);

}

?>

