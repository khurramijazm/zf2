<?php

/*
 * Created on Dec 4, 2012
 * Author: Mian Khurram Ijaz 
  */

namespace Admin;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Db\TableGateway\TableGateway;
use Admin\Model\PagesTable;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\ControllerManager;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $eventManager->getSharedManager()
            ->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, array($this, 'preDispatch'), 1);
       
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
     
    }

   public function getControllerConfig()
    {
       /**
        * For this to work here you have to remove  Dashboard from the module.config.php file 
        * from the invokeables array. It will work only then.  
        */
       return array(
         'factories'  => array(
           'Admin\Controller\Dashboard'  => function(ControllerManager $cm){
                $sm = $cm->getServiceLocator();
                $auth = $sm->get('Admin\Authentication\Service');
                /**
                 * Check if the user is loggged in ? only then return 
                 * the instance of DashboardController other wise 
                 * return the instance of RedirectController which in our case is LoginpageController
                 * and in its  indexAction we will redirect to the Login Page . 
                 * 
                 * We have to return a valid controller here.  
                 */
                if($auth->hasIdentity()){                    
                    $controller = new Controller\DashboardController;                
                    return $controller;
                }
                else
                {
                    $controller = new Controller\LoginPageController;
                    return $controller;
                }
           }
         ),
       );
       
       
    }
    
    public function preDispatch(\Zend\Mvc\MvcEvent $e)
    {
        /**
         * In our case preDispatch will be called on the LoginpageController because 
         * we have returned the instance of LoginpageController in the getControllerConfig() so 
         * it will stop the Routing to 
         * localhost/Dashboard
         * localhost/Dashboard/edit
         * localhost/Dashboard/edit/11
         * 
         * Without this approach the preDispatch was only taking care of the 
         * localhost/Dashboard Route , and 
         * was displaying localhost/Dashboard/edit/11 . 
         * 
         */
        $application    = $e->getApplication();
        $serviceManager = $application->getServiceManager();
        $controller = $e->getTarget();
        $route = $controller->getEvent()->getRouteMatch();
        $hit_controller = $route->getParam('__CONTROLLER__');
        if(strcmp($hit_controller,"Dashboard")==0){
            $authService = $serviceManager->get('Admin\Authentication\Service');
            if (!$authService->hasIdentity()) {
                $pluginManager  = $serviceManager->get('Zend\Mvc\Controller\PluginManager');
                $urlPlugin      = $pluginManager->get('url');
                $redirectPlugin = $pluginManager->get('redirect');
                return $redirectPlugin->toRoute('Admin',array('controller'=>'Admin','action'=>'index'));
            }
        }
        return;
    }    
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
     public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Application\Config\DbAdapter' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    return $dbAdapter;
                },
                 'Admin\Model\PagesTable' => function($sm){
                     $dbAdapter    = $sm->get('Zend\Db\Adapter\Adapter');
                     $tableGateway = new TableGateway('pages', $dbAdapter);
                     $pagesTable   = new PagesTable($tableGateway);
                    return $pagesTable;
                },
                'Admin\Authentication\Service' => function($sm){
                    /**
                     * I have not found a way to instantiate AuthenticationService instance once only and resuse it again.
                     * The questions are not answered on the Mailing list not even on stackoverflow
                     * so it returns a new instance of AuthenticationService() every time 
                     * but The new instance will always use the same Session/Storage. huh it works but needs to dig 
                     * more ...
                     *  
                     */
                    return new \Zend\Authentication\AuthenticationService();
                },
            ),
        );
    }
}
?>
