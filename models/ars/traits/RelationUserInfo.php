<?php
namespace thinker_g\UserAuth\models\ars\traits;

use thinker_g\UserAuth\models\ars\UserInfo;

/**
 * Use this trait when "User" model needs relations of extra user information.
 * The corresponding database table must be created first.
 * @author Thinker_g
 *
 */
trait RelationUserInfo
{
    /**
     * Get additional information for current user.
     * @return ActiveQuery
     */
    public function getUserInfo()
    {
        return $this->hasOne(UserInfo::className(), ['user_id' => 'id']);
    }
}

?>