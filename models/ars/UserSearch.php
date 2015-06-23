<?php

namespace thinker_g\UserAuth\models\ars;

use Yii;
use thinker_g\UserAuth\models\ars\User;
use thinker_g\UserAuth\models\ars\traits\UserSearch as SearchTrait;

/**
 * UserSearch represents the model behind the search form about `thinker_g\UserAuth\models\User`.
 */
class UserSearch extends User
{
    use SearchTrait;
}
