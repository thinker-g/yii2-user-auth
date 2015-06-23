<?php
namespace thinker_g\UserAuth\models\ars;

use Yii;
use thinker_g\UserAuth\models\ars\UserExtAccount;
use thinker_g\UserAuth\models\ars\traits\UserExtAccountSearch as SearchTrait;

/**
 * UserExtAccountSearch represents the model behind the search form about `thinker_g\UserAuth\models\ars\UserExtAccount`.
 */
class UserExtAccountSearch extends UserExtAccount
{
    use SearchTrait;
}
