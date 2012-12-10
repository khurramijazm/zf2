<?php

/*
 * Created on Dec 4, 2012
 * Author: Mian Khurram Ijaz (khurramijazm@gmail.com)
 * Copyright 2012 NextBridge Vteams USA. All rights reserved.
 * COMPANY PROPRIETARY/CONFIDENTIAL. Use is subject to license terms.
 */

namespace Admin;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Db\TableGateway\TableGateway;
use Admin\Model\PagesTable;
use Zend\Authentication\AuthenticationService;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
//        $eventManager->getSharedManager()
//            ->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, array($this, 'preDispatch'), 1);
       
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
     
    }

   public function getControllerConfig()
    {
       echo 'inside getControllerConfig()';
        return array('factories' => array(
            'Admin\check_login' => function ($controllers) {
                $services   = $controllers->getServiceLocator();
                $controller = new Controller\MyController();
                $events     = $services->get('EventManager');
                $events->attach('dispatch', function ($e) use ($controller) {
                    $request = $e->getRequest();
                    $method  = $request->getMethod();
                    var_dump($request);
                    var_dump($method);
                    //return $controller->redirect()->toRoute(/* ... */);
                }, 100); // execute before executing action logic

                $controller->setEventManager($events);
                return $controller;
            }
        ));
    }
    
//    public function preDispatch(\Zend\Mvc\MvcEvent $e)
//    {
//                 
//        $application    = $e->getApplication();
//        $serviceManager = $application->getServiceManager();
//        $controller = $e->getTarget();
//        $route = $controller->getEvent()->getRouteMatch();
//        $hit_controller = $route->getParam('__CONTROLLER__');
//        if(strcmp($hit_controller,"Dashboard")==0){
//            $authService = $serviceManager->get('Admin\Authentication\Service');
//            if (!$authService->hasIdentity()) {
//                $pluginManager  = $serviceManager->get('Zend\Mvc\Controller\PluginManager');
//                $urlPlugin      = $pluginManager->get('url');
//                $redirectPlugin = $pluginManager->get('redirect');
//                return $redirectPlugin->toRoute('Admin',array('controller'=>'Admin','action'=>'index'));
//            }
//        }
//        return;
//    }    
    
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
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $pagesTable = new PagesTable(new TableGateway('pages',$dbAdapter) );
                    return $pagesTable;
                },
                'Admin\Authentication\Service' => function($sm){
                    return new AuthenticationService();
                    
                }
            ),
        );
    }
}
?>
