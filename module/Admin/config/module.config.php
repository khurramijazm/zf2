<?php

/*
 * Admin/config/module.config.php
 * Created on Dec 4, 2012
 * Author: Mian Khurram Ijaz (khurramijazm@gmail.com)
 * Copyright 2012 NextBridge Vteams USA. All rights reserved.
 * COMPANY PROPRIETARY/CONFIDENTIAL. Use is subject to license terms.
 */

return array(
    'router' => array(
        'routes' => array(
            'Dashboard' => array(
              'type' => 'Segment',
              'options' => array(
                  'route' => '/Dashboard[/:action][/:id]',
                  'defaults' => array(
                      '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Dashboard',
                        'action'        => 'index',
                  ),
                  
              ),
            ),
            'Admin' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/Admin[/:action][/:id]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Admin',
                        'action'        => 'index',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Admin' => 'Admin\Controller\AdminController',
            'Admin\Controller\Dashboard' => 'Admin\Controller\DashboardController'
        ),
    ),
    'view_manager' => array(
//        'display_not_found_reason' => true,
//        'display_exceptions'       => true,
//        'doctype'                  => 'HTML5',
//        'not_found_template'       => 'error/404',
//        'exception_template'       => 'error/index',
//        'template_map' => array(
//            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
//            'admin/index/index' => __DIR__ . '/../view/admin/index/index.phtml',
//            'admin/dashboard/index' => __DIR__ . '/../view/admin/dashboard/welcome.phtml',
//            'error/404'               => __DIR__ . '/../view/error/404.phtml',
//            'error/index'             => __DIR__ . '/../view/error/index.phtml',
//        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);

?>
