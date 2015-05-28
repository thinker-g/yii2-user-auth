<?php
namespace thinker_g\UserAuth\models;

use Yii;
use yii\base\Model;
use thinker_g\UserAuth\models\traits\SuperAgentAccountSearch as SearchTrait;

/**
 * SuperAgentAccountSearch represents the model behind the search form about `thinker_g\UserAuth\models\SuperAgentAccount`.
 */
class SuperAgentAccountSearch extends SuperAgentAccount
{
    use SearchTrait;
}
