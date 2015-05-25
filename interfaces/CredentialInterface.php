<?php
namespace thinker_g\UserAuth\interfaces;

/**
 * Model used for authenticating users needs implement this interface.
 *
 * This is mainly required by login process (LoginForm) and user model update process.
 * @author Thinker_g
 *
 */
interface CredentialInterface
{
    /**
     * Find an identity model by given login, the login can be username or email.
     * @param string $login
     * @return \yii\web\IdentityInterface
     */
    public static function findByLogin($login);
    /**
     * Find an credential model by given token. This is used for resetting password.
     * @param string $token
     * @return CredentialInterface
     */
    public static function findByPasswordResetToken($token);

    /**
     * Get unencrypted password, this will be called when model validation is not passed, to prepopulate the field.
     * @return string
     */
    public function getPassword();

    /**
     * Set password for encryption.
     * @param string $password
     */
    public function setPassword($password);

    /**
     * Generate password reset token and store it in model attribute.
     */
    public function generatePasswordResetToken();

    /**
     * Validate password reset token.
     * @param string $token
     * @return bool Whether token is valid.
     */
    public static function isPasswordResetTokenValid($token);

    /**
     * Remove password reset token, this will be called after the password is successfully reset.
     */
    public function removePasswordResetToken();
}

?>