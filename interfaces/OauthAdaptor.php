<?php
namespace thinker_g\UserAuth\interfaces;
use yii\web\Controller;

interface OauthAdaptor
{
    /**
     * Get ID of third-party application, client ID in OAuth2.0.
     * @return string Client ID.
     */
    public function getClientId();

    /**
     * Get Secret of 3rd-party application, client secret in OAuth2.0.
     * @return string Client Secret.
     */
    public function getClientSecret();

    /**
     * Get URL of resource server.
     * @param \yii\web\Controller $controller
     * @return string Login URL of authorization server.
     */
    public function getLoginUrl(Controller $controller);

    /**
     * Method called in the action of callback URL.
     * @param Controller $controller
     * @return string Response from auth
     */
    public function authBack(Controller $controller);

    /**
     * Get Resource Owner's ID.
     * @param string $accessToken
     * @return string Resource owner ID.
     */
    public function getOwnerId($accessToken);
    /**
     * Fetch resource.
     * @param mixed $resource
     * @param string $accessToken
     * @return string Response from authorization server.
     */
    public function fetchResource($resource, $accessToken);
}

?>