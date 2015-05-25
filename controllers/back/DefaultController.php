<?php

namespace thinker_g\UserAuth\controllers\back;

use thinker_g\UserAuth\controllers\BaseDefaultController;

/**
 * Default backend controller.
 *
 * @author Thinker_g
 */
class DefaultController extends BaseDefaultController
{

    /**
     * @inheritdoc
     * @see \yii\base\Object::init()
     */
    public function init()
    {
        parent::init();
        $this->getView()->params['sidebarMenu'] = $this->module->baseBackendNavMenu;
    }
}
