<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Project;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return array(
            'router' => array(
                'routes' => array(
                    'project' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            // Change this to something specific to your module
                            'route'    => '/project[/:controller[/:action[/:id]]]',
                        	'constraints' => array(
                        		'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        		'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        		'part' => '[0-9]*',
                        	),
                            'defaults' => array(
                                // Change this value to reflect the namespace in which
                                // the controllers for your module are found
                                '__NAMESPACE__' => 'Project\Controller',
                                'controller'    => 'index',
                                'action'        => 'index',
                            ),
                        ),
                    ),
                ),
            ),
            'view_manager' => array(
            		'template_path_stack' => array(
            				'project' => __DIR__ . '/View',
            		),
            ),
            'controllers' => array(
                'invokables' => array(
                    'Project\Controller\Index' => 'Project\Controller\IndexController',
                ),
            ),
            
        );

    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../' . __NAMESPACE__,
                ),
            ),
        );
    }
}
