<?php
namespace thinker_g\UserAuth\interfaces;
use thinker_g\Helpers\controllers\ModelViewController as Controller;

interface Oauth2Adaptor
{
    /**
     * Get URL of resource server.
     * @param string $csrfToken
     * @return string Authentication URL of authorization server.
     */
    public function getAuthUrl($csrfToken = null);

    /**
     * Method called in the action of callback URL.
     * @param Controller $controller
     * @return string Response from auth
     */
    public function authBack(Controller $controller);

    /**
     * Get Resource Owner's open user ID.
     * @param array $accessToken
     * @return string Resource owner ID.
     */
    public function fetchOpenUid($accessToken);
    /**
     * Fetch resource.
     * @param mixed $resource
     * @param string $accessToken
     * @param string $key
     * @param bool $assco
     * @return string Response from authorization server.
     */
    public function fetchResource($resource, $accessToken, $key = null, $assco = true);
}

?>
