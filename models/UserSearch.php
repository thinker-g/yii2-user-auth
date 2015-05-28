<?php

namespace thinker_g\UserAuth\models;

use Yii;
use thinker_g\UserAuth\models\User;
use thinker_g\UserAuth\models\traits\UserSearch as SearchTrait;

/**
 * UserSearch represents the model behind the search form about `thinker_g\UserAuth\models\User`.
 */
class UserSearch extends User
{
    use SearchTrait;
}
