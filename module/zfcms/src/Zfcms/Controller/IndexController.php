<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zfcms\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;


class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $auth = new AuthenticationService();
        if($auth->hasIdentity()){
            echo " You are also logged into the Admin Panel ".$auth->getIdentity();
        }
        
        return new ViewModel();
    }
}
