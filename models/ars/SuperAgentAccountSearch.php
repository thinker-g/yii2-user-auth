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
use yii\base\Model;
use thinker_g\UserAuth\models\ars\traits\SuperAgentAccountSearch as SearchTrait;

/**
 * SuperAgentAccountSearch represents the model behind the search form about `thinker_g\UserAuth\models\SuperAgentAccount`.
 */
class SuperAgentAccountSearch extends SuperAgentAccount
{
    use SearchTrait;
}
