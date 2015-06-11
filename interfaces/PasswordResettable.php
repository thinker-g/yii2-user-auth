<?php
namespace thinker_g\UserAuth\interfaces;

/**
 * Interface for resetting user password.
 *
 * This is mainly required by password reset process (ResetPasswordForm) for front-end users.
 * @author Thinker_g
 *
 */
interface PasswordResettable extends PasswordSettable
{
    /**
     * Find an credential model by given token. This is used for resetting password.
     * @param string $token
     * @return CredentialInterface
     */
    public static function findByPasswordResetToken($token);

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