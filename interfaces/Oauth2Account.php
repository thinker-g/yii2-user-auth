<?php
namespace thinker_g\UserAuth\interfaces;

interface Oauth2Account
{
    public static function findByOpenUid($openUid, $from_source);
    public static function findByUserId($userId, $from_source);
    public function getUserId();
    public function setUserId($userId);
    public function delete();
}

?>
