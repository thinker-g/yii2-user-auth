<?php
namespace thinker_g\UserAuth\console;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use thinker_g\UserAuth\models\ars\User;

/**
 * User add command.
 * @author Thinker_g
 *
 */
class UserCommand extends Controller
{
    /**
     * User model configuration.
     * @var string|array
     */
    public $userModel = 'thinker_g\UserAuth\models\ars\User';
    /**
     * Super agent account model configuration.
     * @var unknown
     */
    public $agentAcctModelClass = 'thinker_g\UserAuth\models\ars\SuperAgentAccount';

    /**
     * Status of the user, default to "alive" status.
     * @var string
     */
    public $status = User::STATUS_ALIVE;

    /**
     * Primary email of the user.
     * @var string
     */
    public $email;

    /**
     * Agent account type while granting super agent account.
     * @var string
     */
    public $agent_type = 'super_admin';

    /**
     * @inheritdoc
     * @see \yii\console\Controller::options()
     */
    public function options($actionID)
    {
        $options = [
            'add' => ['email', 'status'],
            'grant-agent' => ['agent_type'],
        ];
        return isset($options[$actionID]) ? $options[$actionID] : [];
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
     * Add a user. This is mainly for adding the very first user of the website.
     * @param string $username Username to add.
     * @param string $password Password of the user.
     * @return boolean
     */
    public function actionAdd($username, $password)
    {
        $user = Yii::createObject($this->userModel);
        // $user = new \thinker_g\UserAuth\models\ars\User();
        $user->username = $username;
        $user->password = $password;

        if ($user->hasAttribute('primary_email')) {
            $user->primary_email = $this->email;
        }
        if ($user->hasAttribute('status')) {
            $user->status = $this->status;
        }
        if ($isSucceeded = $user->save()) {
            $this->stdout("User added: <ID: {$user->id}>{$username}." . PHP_EOL, Console::FG_GREEN);
        } else {
            $this->stdout("Fail to add user: {$username}." . PHP_EOL, Console::FG_RED);
            foreach ($user->getErrors() as $errs)
                $errors[] = implode($errs, PHP_EOL);
            $this->stderr(implode(PHP_EOL, $errors) . PHP_EOL, Console::FG_RED);
        }
        return $isSucceeded ? self::EXIT_CODE_NORMAL : self::EXIT_CODE_ERROR;
    }

    /**
     * Grant a super agent account with a new password to an exsiting user ID.
     * @param int $user_id Target user ID.
     * @param string $agent_password Super password of this user for logging in backend of the site.
     * @return boolean
     */
    public function actionGrantAgent($user_id, $agent_password)
    {
        $superAgentAcct = Yii::createObject($this->agentAcctModelClass);
        // $superAgentAcct = new \thinker_g\UserAuth\models\SuperAgentAccount();
        if (!array_key_exists($this->agent_type, $superAgentAcct::availableSources())) {
            $notice = "Account type: {$this->agent_type} cannot be found in current account model, continue?";
            if (!$this->confirm(Console::ansiFormat($notice, [Console::FG_YELLOW]), false)) {
                $this->stdout("User canceled." . PHP_EOL, Console::FG_GREEN);
                return self::EXIT_CODE_NORMAL;
            }
        }
        $superAgentAcct->from_source = $this->agent_type;
        $superAgentAcct->user_id = $user_id;
        $superAgentAcct->password = $agent_password;
        if ($isSucceeded = $superAgentAcct->save()) {
            $this->stdout("Agent account granted." . PHP_EOL, Console::FG_GREEN);
        } else {
            $this->stdout("Operation failed." . PHP_EOL, Console::FG_RED);
            foreach ($superAgentAcct->getErrors() as $errs)
                $errors[] = implode($errs, PHP_EOL);
            $this->stderr(implode(PHP_EOL, $errors) . PHP_EOL, Console::FG_RED);
        }

        return $isSucceeded ? self::EXIT_CODE_NORMAL : self::EXIT_CODE_ERROR;
    }
}

?>