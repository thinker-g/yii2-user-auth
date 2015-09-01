<?php
/**
 * @link https://github.com/thinker-g/yii2-user-auth
 * @copyright Copyright (c) Thinker_g
 * @license MIT
 * @version v0.0.1
 * @author Thinker_g
 * @since v0.0.1
 */

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