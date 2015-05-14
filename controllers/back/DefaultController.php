<?php

namespace thinker_g\UserAuth\controllers\back;

use thinker_g\UserAuth\controllers\BaseDefaultController;

class DefaultController extends BaseDefaultController
{
    public function init()
    {
        parent::init();
        $this->getView()->params['sidebarMenu'] = $this->module->baseBackendNavMenu;
    }
}
