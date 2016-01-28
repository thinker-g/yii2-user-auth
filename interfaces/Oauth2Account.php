<?php
namespace thinker_g\UserAuth\interfaces;

interface Oauth2Account
{
    public function findByOpenUid($openUid, $from_source);
    public function findByUserId($userId, $from_source);
    public function getUserId();
    public function delete();
}

?>
