<?php
namespace thinker_g\UserAuth\models;

use Yii;
use thinker_g\UserAuth\models\UserExtAccount;
use thinker_g\UserAuth\models\traits\UserExtAccountSearch as SearchTrait;

/**
 * UserExtAccountSearch represents the model behind the search form about `thinker_g\UserAuth\models\UserExtAccount`.
 */
class UserExtAccountSearch extends UserExtAccount
{
    use SearchTrait;
}
