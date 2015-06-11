<?php
namespace thinker_g\UserAuth\interfaces;

/**
 * Interface for finding a user model by login.
 *
 * This is mainly required by any process that needs to find user model by login(email).
 * @author Thinker_g
 *
 */
interface FindByLogin
{
    /**
     * Find an identity model by given login, the login can be username or email.
     * @param string $login
     * @return \yii\web\IdentityInterface
     */
    public static function findByLogin($login);

}

?>