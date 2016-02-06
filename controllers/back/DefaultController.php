<?php
/**
 * @link https://github.com/thinker-g/yii2-user-auth
 * @copyright Copyright (c) Thinker_g
 * @license MIT
 * @version v0.0.1
 * @author Thinker_g
 * @since v0.0.1
 */

namespace thinker_g\UserAuth\controllers\back;

use thinker_g\UserAuth\controllers\BaseDefaultController;
use Yii;

/**
 * Default backend controller.
 *
 * @author Thinker_g
 */
class DefaultController extends BaseDefaultController
{
    public $controllerMvMap = [
        ['model' => 'thinker_g\UserAuth\models\ars\User'],
    ];
    public function actionIndex()
    {
        $modelClassName = self::classNameFromConf($this->getModelClass());
        $reflection = new \ReflectionClass($modelClassName);
        if ($reflection->hasMethod('getStatsByStatus')) {
            $stats = $modelClassName::getStatsByStatus();
        } else {
            $stats = null;
        }
        return $this->render($this->viewID, [
            'model' => Yii::createObject($modelClassName),
            'stats' => $stats
        ]);
    }
}
