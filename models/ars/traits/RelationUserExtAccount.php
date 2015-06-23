<?php
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
     * Get super agent account of current user.
     * @return ActiveQuery
     */
    public function getSuperAgentAcct()
    {
        return $this->hasOne(UserExtAccount::className(), ['user_id' => 'id'])
        ->where(['from_source' => UserExtAccount::SRC_SUPER_AGENT]);
    }

    /**
     * Get ext accounts of current user.
     * @return ActiveQuery
     */
    public function getUserExtAccounts()
    {
        return $this->hasMany(UserExtAccount::className(), ['user_id' => 'id']);
    }
}

?>