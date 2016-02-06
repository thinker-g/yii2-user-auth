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

use thinker_g\UserAuth\models\ars\UserExtAccount;
use thinker_g\UserAuth\models\ars\traits\UserExtAccountSearch as SearchTrait;

/**
 * UserExtAccountSearch represents the model behind the search form about `thinker_g\UserAuth\models\ars\UserExtAccount`.
 */
class UserExtAccountSearch extends UserExtAccount
{
    use SearchTrait;
}
