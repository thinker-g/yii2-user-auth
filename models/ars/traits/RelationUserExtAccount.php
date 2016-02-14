<?php
/**
 * @link https://github.com/thinker-g/yii2-user-auth
 * @copyright Copyright (c) Thinker_g
 * @license MIT
 * @version v0.0.1
 * @author Thinker_g
 * @since v0.0.1
 */

namespace thinker_g\UserAuth\models\ars\traits;

use thinker_g\UserAuth\models\ars\UserExtAccount;

/**
 *
 * Use this trait when "User" model needs relations of External Accounts and Super Agent Account.
 * The corresponding database table must be created first.
 * @author Thinker_g
 *
 */
trait RelationUserExtAccount
{

    /**
     * Get ext accounts of current user.
     * @return ActiveQuery
     */
    public function getUserExtAccounts()
    {
        return $this->hasMany(UserExtAccount::className(), ['user_id' => 'id'])
            ->where(['from_source' => array_keys(UserExtAccount::availableSources())]);
    }
}

?>