<?php
namespace thinker_g\UserAuth\controllers\back;

use thinker_g\UserAuth\controllers\BaseDefaultController;
use yii\base\Exception;
use yii\base\ErrorException;
use Yii;

/**
 * Default backend controller.
 *
 * @author Thinker_g
 */
class DefaultController extends BaseDefaultController
{
    public $moduleMvMapAttr = 'backMvMap';
    public function actionIndex()
    {
        $modelClassName = self::classNameFromConf($this->getActionMvMap()['model']);
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
