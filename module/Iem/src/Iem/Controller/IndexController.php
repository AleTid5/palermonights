<?php

/**
 *
 */

namespace Iem\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {

    public function indexAction() {
            return $this->redirect()->toUrl('/user/login');
    }

}
