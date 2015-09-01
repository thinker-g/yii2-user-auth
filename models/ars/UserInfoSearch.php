<?php
/**
 * @link https://github.com/thinker-g/yii2-user-auth
 * @copyright Copyright (c) Thinker_g
 * @license MIT
 * @version v0.0.1
 * @author Thinker_g
 * @since v0.0.1
 */

namespace thinker_g\UserAuth\models\ars;

use Yii;
use thinker_g\UserAuth\models\ars\UserInfo;
use thinker_g\UserAuth\models\ars\traits\UserInfoSearch as SearchTrait;

/**
 * UserInfoSearch represents the model behind the search form about `thinker_g\UserAuth\models\ars\UserInfo`.
 */
class UserInfoSearch extends UserInfo
{
    use SearchTrait;
}
