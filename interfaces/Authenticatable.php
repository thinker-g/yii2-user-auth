<?php
namespace thinker_g\UserAuth\interfaces;

/**
 * Interface for finding a user model by login and authenticating current user who's trying to login.
 *
 * This is mainly required by any process that needs to find user model by login(email).
 * @author Thinker_g
 *
 */
interface Authenticatable
{
    /**
     * Find an identity model by given login, the login can be username or email.
     * @param string $login
     * @return \yii\web\IdentityInterface
     */
    public static function findByLogin($login);

    /**
     * Method to validate whether the password submitted from current user matches the one stored in the user model.
     * @param string $password The password from login form subbmitted by current user.\
     * @return boolean
     */
    public function validatePassword($password);

}

?>