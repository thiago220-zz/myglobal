<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'app' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/[:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'cliente' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/cliente[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*'
                    ],
                    'defaults' => [
                        'controller' => Controller\ClienteController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\ClienteController::class => Controller\Factory\ClienteControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    
    'view_helpers' => [
        'factories' => [
            View\Helper\Menu::class => InvokableFactory::class, 
            View\Helper\Breadcrumbs::class => InvokableFactory::class,
        ],
       'aliases' => [
            'mainMenu' => View\Helper\Menu::class,
            'pageBreadcrumbs' => View\Helper\Breadcrumbs::class,
       ]
    ], 
    
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ]  ,
    'session_containers' => [
        'MyGlobal'
    ],
    
    'access_filter' => [
        'options' => [
            'mode' => 'restrictive'
        ],
        'controllers' => [
            Controller\IndexController::class => [
                ['actions' => ['index'], 'allow' => '*'],
            ],
            Controller\ClienteController::class => [
                // Give access to "resetPassword", "message" and "setPassword" actions
                // to anyone.
                ['actions' => [
                    'index',
                    'contratos',
                    'nuevo',
                    'editar',
                    'detalles',
                    'contacto',
                    'confirmacion',
                    'error',
                    'todos',
                    ], 'allow' => '*'],
                // Give access to "index", "add", "edit", "view", "changePassword" actions to users having the "user.manage" permission.
                ['actions' => ['eliminar'], 'allow' => '+user.manage']
            ],
        ]
    ],
    // This key stores configuration for RBAC manager.
    'rbac_manager' => [
        'assertions' => [Service\RbacAssertionManager::class],
    ],
    'service_manager' => [
        'factories' => [
            Service\NavManager::class => Service\Factory\NavManagerFactory::class,
            Service\RbacAssertionManager::class => Service\Factory\RbacAssertionManagerFactory::class,
            Service\MailSender::class => InvokableFactory::class,
            Service\ClienteManager::class => Service\Factory\ClienteManagerFactory::class,        
        ],
    ],
];
