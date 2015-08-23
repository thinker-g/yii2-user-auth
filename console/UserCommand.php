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
        if ($this->agent_type && !array_key_exists($this->agent_type, $superAgentAcct::availableSources())) {
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

    /**
     * List agent accounts granted to a user by user id.
     * @param int $user_id
     */
    public function actionListAgents($user_id)
    {
        $superAgentAcct = Yii::createObject($this->agentAcctModelClass);
        $accts = $superAgentAcct::find()
        ->asArray()
        ->select(['from_source'])
        ->where([
            'user_id' => $user_id,
            'from_source' => $superAgentAcct::availableSources(),
        ])
        ->column();
        $this->stdout("Agent accounts granted to user <ID: {$user_id}>:\n\t");
        if (empty($accts)) {
            $this->stdout('No agent accounts found.' . PHP_EOL, Console::FG_YELLOW);
        } else {
            $this->stdout(implode("\n\t", $accts) . PHP_EOL . PHP_EOL);
        }

        return self::EXIT_CODE_NORMAL;
    }

    /**
     * Revoke agent account from a user by user id.
     *
     * @param int $user_id
     * @param string $agent_type
     */
    public function actionRevokeAgent($user_id, $agent_type)
    {
        $superAgentAcct = Yii::createObject($this->agentAcctModelClass);
        // $superAgentAcct = new \thinker_g\UserAuth\models\SuperAgentAccount();
        if (!array_key_exists($agent_type, $superAgentAcct::availableSources())) {
            $warning = "Account type: {$agent_type} cannot be found in current account model.\n";
            $this->stdout($warning, Console::FG_YELLOW);
        }
        $targetAcct = $superAgentAcct::find()
        ->where(['user_id' => $user_id, 'from_source' => $agent_type])
        ->one();
        if ($targetAcct) {
            $warning = "Revoke {$targetAcct->from_source} from user <ID: {$user_id}>?";
            if ($this->confirm(Console::ansiFormat($warning, [Console::FG_RED]), false)) {
                if ($targetAcct->delete()) {
                    $this->stdout("Agent revoked." . PHP_EOL, Console::FG_GREEN);
                    return self::EXIT_CODE_NORMAL;
                } else {
                    $this->stderr("No agent revoked, please try again." . PHP_EOL, console::FG_RED);
                    return self::EXIT_CODE_ERROR;
                }
            } else {
                $this->stdout("User canceled." . PHP_EOL, Console::FG_GREEN);
                return self::EXIT_CODE_NORMAL;
            }
        } else {
            $this->stdout('No agent account found.' . PHP_EOL, Console::FG_GREEN);
            return self::EXIT_CODE_NORMAL;
        }
    }
}

