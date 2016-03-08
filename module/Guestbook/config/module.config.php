<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Guestbook\Controller\Index' => 'Guestbook\Controller\IndexController',
            'Guestbook\Controller\Admin' => 'Guestbook\Controller\AdminController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'guestbook' => array(
                'type'    => 'Segment',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/guestbook[/:controller[/:action[/:id]]]',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Guestbook\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]*',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Guestbook' => __DIR__ . '/../view',
        ),
    ),

    'guestbook_guard' => array(
        'allow' => array(
            array(array('admin', 'guest'), 'Guestbook\Controller\Index'),
            array(array('admin'), 'Guestbook\Controller\Admin'),
            array(array('guest'), 'Guestbook\Controller\Admin', 'login'),
        ),
        'deny' => array(
            array(array('guest'), 'Guestbook\Controller\Admin'),
        ),
        'admin_password_hash' => '5701e1fc38a45821bc7687a3d8530720', //admin_test
    ),
);
