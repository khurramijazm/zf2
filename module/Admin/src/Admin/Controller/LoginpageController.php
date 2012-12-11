<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class LoginpageController extends AbstractActionController
{

    public function indexAction()
    {
        $this->redirect()->toRoute('Admin');
        return false;
    }
}

?>
