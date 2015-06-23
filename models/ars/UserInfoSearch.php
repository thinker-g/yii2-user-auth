<?php

namespace thinker_g\UserAuth\models\ars;

use Yii;
use thinker_g\UserAuth\models\ars\UserInfo;
use thinker_g\UserAuth\models\ars\traits\UserInfoSearch as SearchTrait;

/**
 * UserInfoSearch represents the model behind the search form about `thinker_g\UserAuth\models\UserInfo`.
 */
class UserInfoSearch extends UserInfo
{
    use SearchTrait;
}
