<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'mailer' => array(
                'type'    => 'Segment',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/mailer[/:controller[/:action[/:folder[/:message[/:id]]]]]',
                	'constraints' => array(
                		'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                		'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                		'folder' => '[a-zA-Z][a-zA-Z0-9_-]*',
                		'message' => '[0-9_-]*',
                		'part' => '[0-9_-]*',
                	),
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Mailer\Controller',
                        'controller'    => 'index',
                        'action'        => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
    		'template_path_stack' => array(
    				'mailer' => __DIR__ . '/../View',
    		),
    ),
    'controllers' => array(
        'invokables' => array(
            'Mailer\Controller\Index' => 'Mailer\Controller\IndexController',
            'Mailer\Controller\File' => 'Mailer\Controller\FileController'
        ),
    ),
    
);
