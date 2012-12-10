<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\Login;
use Admin\Form\LoginForm;
use Zend\Authentication\AuthenticationService;

use Zend\Authentication\Adapter\DbTable as dbTable;

class AdminController extends AbstractActionController
{
    
    public function indexAction()
    {
        $form = new LoginForm();
        return array('form' => $form);    
    }
    
    public function logoutAction(){
        $authService = new AuthenticationService();
         if($authService->hasIdentity()){
            $authService->clearIdentity();
            return $this->redirect()->toRoute('Admin');
        }
    }
    public function loginAction(){
        $request = $this->getRequest();
        if($request->isPost()){
            $sm = $this->getServiceLocator();
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');           
            $dbTable = new dbTable($dbAdapter,'users','username','password');
            
            
            $post = $request->getPost();
            $username = $post['username'];
            $password = $post['password'];

            $dbTable->setIdentity($username);
            $dbTable->setCredential($password);
            
            $auth = new AuthenticationService();
            $result = $auth->authenticate($dbTable);
            
            
            if(!$result->isValid()){
                // Authentication failure
                foreach($result->getMessages() as $message){
                    echo "$message\n";
                }
            }
            else{
                $storage = $auth->getStorage();
                $storage->write($username);
                return $this->redirect()->toRoute('Dashboard');
            }
            
            
        }
        
        return array('true');
    }
}
