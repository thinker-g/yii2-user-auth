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
    public $superAgentAcctModel = 'thinker_g\UserAuth\models\SuperAgentAccount';

    /**
     * Status of the user, default to "pending" status.
     * @var string
     */
    public $status = User::STATUS_PENDING;

    /**
     * Primary email of the user.
     * @var string
     */
    public $email;

    /**
     * @inheritdoc
     * @see \yii\console\Controller::options()
     */
    public function options($actionID)
    {
        $options = [
            'add' => ['email', 'status'],
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
            $this->stdout("Added user: [{$user->id}]{$username}." . PHP_EOL, Console::FG_GREEN);
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
     * @param string $super_password Super password of this user for logging in backend of the site.
     * @return boolean
     */
    public function actionGrantSuperAgent($user_id, $super_password)
    {
        $superAgentAcct = Yii::createObject($this->superAgentAcctModel);
        // $superAgentAcct = new \thinker_g\UserAuth\models\SuperAgentAccount();
        $superAgentAcct->from_source = $superAgentAcct::SRC_SUPER_AGENT;
        $superAgentAcct->user_id = $user_id;
        $superAgentAcct->password = $super_password;
        if ($isSucceeded = $superAgentAcct->save()) {
            $this->stdout("Super agent granted." . PHP_EOL, Console::FG_GREEN);
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