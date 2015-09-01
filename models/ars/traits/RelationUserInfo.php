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