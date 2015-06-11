<?php
namespace thinker_g\UserAuth\interfaces;

/**
 * Interface for setting/getting password in clear text.
 *
 * This is mainly required by processes that need to setup password, such as sign-up procedure.
 * @author Thinker_g
 *
 */
interface PasswordSettable
{

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

}

?>