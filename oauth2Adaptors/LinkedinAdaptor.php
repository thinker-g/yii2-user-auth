<?php
namespace thinker_g\UserAuth\oauth2Adaptors;

use thinker_g\UserAuth\oauth2Adaptors\BaseOauth2Adaptor;
use thinker_g\Helpers\controllers\ModelViewController as Controller;
use yii\web\ForbiddenHttpException;
use Yii;

class LinkedinAdaptor extends BaseOauth2Adaptor
{

    public $id = 'linkedin';
    public $clientId;
    public $clientSecret;
    public $apiAuth = 'https://www.linkedin.com/uas/oauth2/authorization';
    public $apiAccToken = 'https://www.linkedin.com/uas/oauth2/accessToken';
    public $apiRes = 'https://api.linkedin.com';
    public $csrfParam = 'state';

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
            'state' => $csrfToken,
            'scope' => $this->scope,
        ];
        $query = http_build_query($params);
        return $this->apiAuth . '?' . $query;
    }

    /**
     * @param string $assco
     * @return array
     */
    public function requestAccessToken()
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
        $data = json_decode($response, true);
        return [
            'token' => $data['access_token'],
            'expiresAt' => $data['expires_in'] + time(),
        ];
    }

    public function fetchOpenUid($accessToken)
    {
        return $this->fetchResource('/v1/people/~:(id)', $accessToken['token'], 'id');
    }

    /**
     * @inheritdoc
     * @see \thinker_g\UserAuth\interfaces\Oauth2Adaptor::fetchResource()
     */
    public function fetchResource($resource, $accessToken, $key = null,  $assco = true)
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
        $result = json_decode($response, $assco);
        if ($assco) {
            return is_null($key) ? $result : $result[$key];
        } else {
            return is_null($key) ? $result : $result->$key;
        }
    }

    /**
     * @inheritdoc
     * @param Controller $controller
     * @throws ForbiddenHttpException
     */
    public function handleAuthFailed(Controller $controller)
    {
        if (Yii::$app->request->get('error')) {
            $controller->view->title = "Error: [" . Yii::$app->request->get('error') . ']';
            $data = ['message' => Yii::$app->request->get('error_description')];
            return $controller->render($controller->viewID, $data);
        } else {
            throw new ForbiddenHttpException('Illegal operation.');
        }
    }

    /**
     * @inheritdoc
     * @param array $accessToken
     * @param Controller $controller
     */
    public function handleGuestNoAcct(array $accessToken, Controller $controller)
    {
        $res = $this->fetchResource('/v1/people/~:(id,email-address,first-name)', $accessToken['token']);

        $user = $this->createUser([
            'primary_email' => $res['emailAddress'],
            'display_name' => $res['firstName'],
        ]); // TODO creation validation failed.
        $this->bindAccount($user, [
            'user_id' => Yii::$app->user->id,
            'from_source' => $this->id,
            'open_uid' => $res['id'],
            'access_token' => $accessToken['token'],
            'acctoken_expires_at' => date('Y-m-d H:i:s', $accessToken['expiresAt']),
        ]);
        Yii::$app->user->login($user) && $controller->goBack();
        return $controller->render($controller->viewID, ['message' => 'Something wrong happened, please try again.']);
        // return $controller->render($controller->viewID, ['message' => 'Reg new user and bind this Oauth Account']);
    }

    /**
     * @inheritdoc
     * @param array $accessToken
     * @param string $openUid
     * @param Controller $controller
     * @return string
     */
    public function handleUserNoAcct(array $accessToken, $openUid, Controller $controller)
    {
        if (call_user_func_array([$this->acctModelClass, 'findByUserId'], [Yii::$app->user->getId(), $this->id])) {
            return $controller->render($controller->viewID, ['message' => 'Current user has already bound another Linkedin account']);
        } else {
            $this->bindAccount(Yii::$app->user->getIdentity(true), [
                'user_id' => Yii::$app->user->getId(),
                'from_source' => $this->id,
                'open_uid' => $openUid,
                'access_token' => $accessToken['token'],
                'acctoken_expires_at' => date('Y-m-d H:i:s', $accessToken['expiresAt']),
            ]);
            return $controller->render($controller->viewID, ['message' => 'Linkedin account is bound to current User.']);
        }
    }
}

?>
