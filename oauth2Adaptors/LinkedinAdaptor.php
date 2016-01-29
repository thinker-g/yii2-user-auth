<?php
namespace thinker_g\UserAuth\oauth2Adaptors;

use thinker_g\UserAuth\interfaces\Oauth2Adaptor;
use yii\base\Component;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\base\ErrorException;

class LinkedinAdaptor extends Component implements Oauth2Adaptor
{

    public $id = 'linkedin';
    public $clientId;
    public $clientSecret;
    public $apiAuth = 'https://www.linkedin.com/uas/oauth2/authorization';
    public $apiAccToken = 'https://www.linkedin.com/uas/oauth2/accessToken';
    public $apiRes = 'https://api.linkedin.com';
    public $callbackRoute;
    public $scope;
    public $acctModel = 'thinker_g\UserAuth\models\ars\UserExtAccount';
    public $userModel;

    private $_accessToken;

    /**
     * @inheritdoc
     * @see \thinker_g\UserAuth\interfaces\Oauth2Adaptor::getOpenUid()
     */
    public function getOpenUid($accessToken)
    {
        return $this->fetchResource('/v1/people/~:(id)', $accessToken)['id'];
    }

    /**
     * @inheritdoc
     * @see \thinker_g\UserAuth\interfaces\Oauth2Adaptor::authBack()
     */
    public function authBack(Controller $controller)
    {
        if (!Yii::$app->request->get('code')) {
            if (Yii::$app->request->get('error')) {
                $controller->view->title = "Error: [" . Yii::$app->request->get('error') . ']';
                $data = ['content' => Yii::$app->request->get('error_description')];
                return $controller->render($controller->viewID, $data);
            } else {
                throw new ForbiddenHttpException('Illegal operation.');
            }
        }
        try {
            $accessToken = $this->requestAccessToken();
        } catch (ErrorException $e) {
            $controller->view->title = 'Service Failure';
            return $controller->render($controller->viewID, ['content' => 'Please go back and try again.']);
        }
        $openUid = $this->getOpenUid($accessToken['access_token']);
        if (Yii::$app->getUser()->isGuest) {
            if ($extAcct = call_user_func([$this->acctModel, 'findByOpenUid'], $openUid, $this->id)) {
                return $controller->render($controller->viewID, ['content' => 'Login via Openid.']);
            } else {
            /*// create user and link it to this account model.
            if (!$this->userModel) {
                $this->userModel = Yii::$app->getUser()->identityClass;
            }
            $user = Yii::createObject($this->userModel);
            $user->save(false);
            $acctModel = Yii::createObject($this->acctModel);
            $searchCond['user_id'] = $user->primaryKey;
            $searchCond['access_token'] = $accessToken;
            $acctModel->load($searchCond, '');
            $acctModel->save(false);
            var_dump($acctModel->attributes);*/
                return $controller->render($controller->viewID, ['content' => 'Reg new user and bind this Oauth Account']);
            }
        } else {
            if ($extAcct = call_user_func([$this->acctModel, 'findByOpenUid'], $openUid, $this->id)) {
                if ($extAcct->getUserId() == Yii::$app->getUser()->getId()) {
                    return $controller->render($controller->viewID, ['content' => 'Linkedin account is already bound to current user.']);
                } else {
                    return $controller->render($controller->viewID, ['content' => 'This linkedin account has already been bound on another user.']);
                }
            } else {
                if (call_user_func_array([$this->acctModel, 'findByUserId'], [Yii::$app->getUser()->getId(), $this->id])) {
                    return $controller->render($controller->viewID, ['content' => 'Current user has already bound another Linkedin account']);
                } else {
                    return $controller->render($controller->viewID, ['content' => 'Bind this Linkedin account to current User.']);
                }
            }
        }
    }

    /**
     * @inheritdoc
     * @see \thinker_g\UserAuth\interfaces\Oauth2Adaptor::getAuthUrl()
     */
    public function getAuthUrl($csrfToken = null)
    {
        $params = [
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'redirect_uri' => Yii::$app->urlManager->createAbsoluteUrl($this->callbackRoute),
            'state' => $csrfToken, //TODO Enable csrf validation
            'scope' => $this->scope,
        ];
        $query = http_build_query($params);
        return $this->apiAuth . '?' . $query;
    }

    /**
     *
     * @param string $uid
     * @param bool $assco Set to true to return an array, false to return an StdObject.
     * @param bool $refresh
     * @return mixed
     */
    public function requestAccessToken($uid = null, $assco = true, $refresh = false)
    {
        $redirectUri = Yii::$app->urlManager->createAbsoluteUrl($this->callbackRoute);
        $params = [
            'grant_type' => 'authorization_code',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => Yii::$app->request->get('code'),
            'redirect_uri' => $redirectUri
        ];

        // Access Token request
        $url = $this->apiAccToken . '?' . http_build_query($params);

        // Tell streams to make a POST request
        $context = stream_context_create([
            'http' => ['method' => 'POST']
        ]);

        // Retrieve access token information
        $response = file_get_contents($url, false, $context);

        // Native PHP object, please
        return json_decode($response, $assco);
    }

    /**
     * @inheritdoc
     * @see \thinker_g\UserAuth\interfaces\Oauth2Adaptor::fetchResource()
     */
    public function fetchResource($resource, $accessToken, $assco = true)
    {
        $params = [
            'http' => [
                'method' => 'GET',
                'header' => "Authorization: Bearer " . $accessToken. "\r\n" . "x-li-format: json\r\n"
            ]
        ];

        // Need to use HTTPS
        $url = $this->apiRes . $resource;

        // Tell streams to make a (GET, POST, PUT, or DELETE) request
        // And use OAuth 2 access token as Authorization
        $context = stream_context_create($params);

        // Hocus Pocus
        $response = file_get_contents($url, false, $context);

        // Native PHP object, please
        return json_decode($response, $assco);
    }
}

?>
