<?php
namespace thinker_g\UserAuth\console;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * User add command.
 * @author Thinker_g
 *
 */
class UserCommand extends Controller
{
    public $userModel = 'thinker_g\UserAuth\models\User';
    public $superAgentAcctModel = 'thinker_g\UserAuth\models\SuperAgentAccount';

    public $username;
    public $password;
    public $userid;

    public function options($actionID)
    {
        $options = [
            'add' => ['username', 'password'],
            'grant-super-agent' => ['userid', 'password']
        ];
        return $options[$actionID];
    }

    /**
     * Display usage.
     * @return number
     */
    public function actionIndex()
    {
        $this->run('/help', [$this->id]);
        return 0;
    }

    /**
     * Add a user. Options [[username]] and [[password]] must be specified.
     * @return boolean
     */
    public function actionAdd()
    {
        $user = Yii::createObject($this->userModel);
        // $user = new \thinker_g\UserAuth\models\User();
        $user->username = $this->username;
        $user->password = $this->password;
        $user->status = $user::STATUS_ACTIVE;
        $user->validate(['username', 'password']);
        if ($return = !$user->hasErrors()) {
            if ($return = $user->save(false)) {
                $this->stdout("Added user: [{$user->id}]{$user->username}." . PHP_EOL, Console::FG_GREEN);
            } else {
                $this->stdout("Fail to add user: {$user->username}." . PHP_EOL, Console::FG_RED);
            }
        } else {
            foreach ($user->getErrors() as $errs)
                $errors[] = implode($errs, PHP_EOL);
            $this->stderr(implode(PHP_EOL, $errors) . PHP_EOL, Console::FG_RED);
        }
        return $return;
    }

    /**
     * Grant a user a super agent account. Options [[userid]] and [[password]] must be specified.
     * @return boolean
     */
    public function actionGrantSuperAgent()
    {
        $superAgentAcct = Yii::createObject($this->superAgentAcctModel);
        // $superAgentAcct = new \thinker_g\UserAuth\models\SuperAgentAccount();
        $superAgentAcct->from_source = $superAgentAcct::SRC_SUPER_AGENT;
        $superAgentAcct->user_id = $this->userid;
        $superAgentAcct->password = $this->password;
        $superAgentAcct->validate(['user_id', 'password']);
        if ($return = !$superAgentAcct->hasErrors()) {
            if ($return = $superAgentAcct->save(false)) {
                $this->stdout("Super agent granted." . PHP_EOL, Console::FG_GREEN);
            } else {
                $this->stdout("Operation failed." . PHP_EOL, Console::FG_RED);
            }
        } else {
            foreach ($superAgentAcct->getErrors() as $errs)
                $errors[] = implode($errs, PHP_EOL);
            $this->stderr(implode(PHP_EOL, $errors) . PHP_EOL, Console::FG_RED);
        }
        return $return;
    }
}

?>