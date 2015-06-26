<?php
namespace thinker_g\UserAuth\models\forms;

use yii\base\Model;
use Yii;

/**
 *
 * @author Thinker_g
 *
 */
class CredentialForm extends Model
{
    /**
     * Class name (configuration array) of the user credential model of the form.
     * If set to 'null', the [[identityClass]] of 'user' component of the application will be used.
     * @var string
     */
    public $credentialModelClass;

    /**
     * Get the credential model class.
     *
     * @return string
     */
    public function getCredentialModelClass()
    {
        if (is_null($this->credentialModelClass)) {
            $modelClass = Yii::$app->getUser()->identityClass;
        } else {
            $modelClass = $this->credentialModelClass;
        }
        return $modelClass;
    }
}

?>