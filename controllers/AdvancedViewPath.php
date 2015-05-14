<?php
namespace thinker_g\UserAuth\controllers;

/**
 *
 * @author Thinker_g
 *
 */
trait AdvancedViewPath
{
    /**
     * @overriding
     * @see \yii\base\Controller::getViewPath()
     */
    public function getViewPath()
    {
        return $this->module->getViewPath()
        . DIRECTORY_SEPARATOR
        . array_pop(explode('\\', $this->module->controllerNamespace))
        . DIRECTORY_SEPARATOR
        . $this->id;
    }
}

?>