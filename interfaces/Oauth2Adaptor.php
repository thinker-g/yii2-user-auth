<?php
namespace thinker_g\UserAuth\interfaces;
use yii\web\Controller;

interface Oauth2Adaptor
{
    /**
     * Get URL of resource server.
     * @param string $csrfToken
     * @return string Authentication URL of authorization server.
     */
    public function getAuthUrl($csrfToken);

    /**
     * Method called in the action of callback URL.
     * @param Controller $controller
     * @return string Response from auth
     */
    public function authBack(Controller $controller);

    /**
     * Get Resource Owner's open user ID.
     * @param string $accessToken
     * @return string Resource owner ID.
     */
    public function getOpenUid($accessToken);
    /**
     * Fetch resource.
     * @param mixed $resource
     * @param string $accessToken
     * @param bool $assco
     * @return string Response from authorization server.
     */
    public function fetchResource($resource, $accessToken, $assco = true);
}

?>