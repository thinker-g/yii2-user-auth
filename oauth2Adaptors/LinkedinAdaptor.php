<?php
namespace thinker_g\UserAuth\oauth2Adaptors;

use thinker_g\UserAuth\interfaces\Oauth2Adaptor;
use yii\base\Component;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class LinkedinAdaptor extends Component implements Oauth2Adaptor
{

    public $clientId;
    public $clientSecret;
    public $apiAuth = 'https://www.linkedin.com/uas/oauth2/authorization';
    public $scope;
    public $acctModel = 'thinker_g\UserAuth\models\ars\UserExtAccount';
    public $userModel;
    public $callBackUriParams = ['from_source' => 'linkedin'];

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
            throw new ForbiddenHttpException('Illegal operation.');
        }
        $accessToken = $this->getAccessToken(null, $controller);
        $searchCond = [
            'open_uid' => $this->getOwnerId($accessToken),
            'from_source' => 'linkedin',
        ];
        // $extAcct = \thinker_g\UserAuth\models\ars\UserExtAccount::findOne($searchCond);
        if ($extAcct = call_user_func([$this->acctModel, 'findOne'], $searchCond)) {
            // find user model and log him in.
            echo 'Local user found.';
        } else {
            // create user and link it to this account model.
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
            var_dump($acctModel->attributes);
        }
    }
    
    /**
     * @inheritdoc
     * @see \thinker_g\UserAuth\interfaces\Oauth2Adaptor::getAuthUrl()
     */
    public function getAuthUrl(Controller $controller)
    {
        $data = [
            'response_type' => 'code',
            'client_id' => $this->client,
            'redirect_uri' => Yii::$app->urlManager->createAbsoluteUrl([
                $controller->module->id . '/' . $controller->id . '/back',
                'from_source' => 'linkedin'
            ]),
            'state' => 'csrfToken',
            'scope' => $this->scope,
        ];
        $query = http_build_query($data);
        return $this->apiAuth . '?' . $query;
    }

    /**
     * @param string $redirectUri
     * @param bool $assco Set to true to return an array, and false to return a StdObject.
     */
    protected function getAccessToken($uid = null, Controller $controller = null, $assco = true)
    {
        $this->callBackUriParams[0] = $controller->module->id . '/' . $controller->id . '/back';
        $redirectUri = Yii::$app->urlManager->createAbsoluteUrl($this->callBackUriParams);
        $params = [
            'grant_type' => 'authorization_code',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => Yii::$app->request->get('code'),
            'redirect_uri' => $redirectUri
        ];

        // Access Token request
        $url = 'https://www.linkedin.com/uas/oauth2/accessToken?' . http_build_query($params);

        // Tell streams to make a POST request
        $context = stream_context_create([
            'http' => ['method' => 'POST']
        ]);

        // Retrieve access token information
        $response = file_get_contents($url, false, $context);

        // Native PHP object, please
        $token = json_decode($response, $assco);
        return $token['access_token'];
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
        $url = 'https://api.linkedin.com' . $resource;

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