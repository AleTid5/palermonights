<?php

namespace Iem;

return array(
    'router' => array(
        'routes' => array(
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'iem' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/iem',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Iem\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'es_ES',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Iem\Controller\Index' => 'Iem\Controller\IndexController',
            'Iem\Controller\Iabm' => 'Iem\Controller\IabmController',
            'Iem\Controller\Resources' => 'Iem\Controller\ResourcesController',
            'Iem\Controller\Templates' => 'Iem\Controller\TemplatesController',
            'Iem\Controller\Db' => 'Iem\Controller\DbController',
            'Iem\Controller\Mailinglist' => 'Iem\Controller\MailinglistController',
            'Iem\Controller\Emailsending' => 'Iem\Controller\EmailsendingController',
            'Iem\Controller\Tracing' => 'Iem\Controller\TracingController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
            'widget/trackAnonymousMessagesForm' => __DIR__ . '/../view/widget/track-anonymous-messages-form.phtml',
            'widget/desdeHasta' => __DIR__ . '/../view/widget/desde-hasta.phtml',
            'widget/contactFilter' => __DIR__ . '/../view/widget/contact-filter.phtml',
            'widget/formHorizontal' => __DIR__ . '/../view/widget/form-horizontal.phtml',
             'widget/formHorizontalOne' => __DIR__ . '/../view/widget/form-horizontal-one.phtml',
             'widget/formVertical' => __DIR__ . '/../view/widget/form-vertical.phtml'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Dashboard',
                'uri' => '#',
                'icon' => 'fa fa-dashboard fa-fw',
                'resource' => 'admin',
                'privilege' => 'report',
            ),
            array(
                'label' => 'ABM',
                'uri' => '#',
                'icon' => 'fa fa-sitemap fa-fw',
                'resource' => 'admin',
                'privilege' => 'abm',
                'pages' => array(
                    array(
                        'label' => 'Recursos',
                        'uri' => '#',
                        'icon' => 'fa fa-table',
                        'resource' => 'admin',
                        'privilege' => 'abm',
                        'pages' => array(
                            array(
                                'label' => 'SMTP',
                                'uri' => '/iem/resources/abm-smtp',
                                'icon' => 'fa fa-database',
                                'resource' => 'admin',
                                'privilege' => 'abm',
                            ),
                            array(
                                'label' => 'Cuentas',
                                'uri' => '/iem/resources/abm-email-auth',
                                'icon' => 'fa fa-database',
                                'resource' => 'admin',
                                'privilege' => 'abm',
                            ),
                             array(
                                'label' => 'Email Test',
                                'uri' => '/iem/resources/abm-email-test',
                                'icon' => 'fa fa-database',
                                'resource' => 'admin',
                                'privilege' => 'abm',
                            ),
                        )
                    ),
                    array(
                        'label' => 'Templates',
                        'uri' => '#',
                        'icon' => 'fa fa-table',
                        'resource' => 'admin',
                        'privilege' => 'abm',
                        'pages' => array(
                            array(
                                'label' => 'Agrupaciones',
                                'uri' => '/iem/templates/abm-grouping',
                                'icon' => 'fa fa-database',
                                'resource' => 'admin',
                                'privilege' => 'abm',
                            ),
                            array(
                                'label' => 'Asuntos',
                                'uri' => '/iem/templates/abm-subjects',
                                'icon' => 'fa fa-database',
                                'resource' => 'admin',
                                'privilege' => 'abm',
                            ),
                            array(
                                'label' => 'Textos',
                                'uri' => '/iem/templates/abm-texts',
                                'icon' => 'fa fa-database',
                                'resource' => 'admin',
                                'privilege' => 'abm',
                            ),
                              array(
                                'label' => 'Layout',
                                'uri' => '/iem/templates/abm-layout',
                                'icon' => 'fa fa-database',
                                'resource' => 'admin',
                                'privilege' => 'abm',
                            ),
                        )
                    ),
                    array(
                        'label' => 'Base de Contactos',
                        'uri' => '#',
                        'icon' => 'fa fa-table',
                        'resource' => 'admin',
                        'privilege' => 'abm',
                        'pages' => array(
                            array(
                                'label' => 'General',
                                'uri' => '/iem/db/abm-contacts',
                                'icon' => 'fa fa-database',
                                'resource' => 'admin',
                                'privilege' => 'abm',
                            ),
                            array(
                                'label' => 'Buenos Aires',
                                'uri' => '/iem/db/abm-contacts-bs-as',
                                'icon' => 'fa fa-database',
                                'resource' => 'admin',
                                'privilege' => 'abm',
                            ),
                            array(
                                'label' => 'CABA',
                                'uri' => '/iem/db/abm-contacts-caba',
                                'icon' => 'fa fa-database',
                                'resource' => 'admin',
                                'privilege' => 'abm',
                            ),
                            array(
                                'label' => 'Personalizado',
                                'uri' => '/iem/db/abm-contacts-filter',
                                'icon' => 'fa fa-database',
                                'resource' => 'admin',
                                'privilege' => 'abm',
                            ),
                              array(
                                'label' => 'Nuevos PalermoNights',
                                'uri' => '/iem/db/abm-contacts-new',
                                'icon' => 'fa fa-database',
                                'resource' => 'admin',
                                'privilege' => 'abm',
                            ),
                             array(
                                'label' => 'Login PalermoNights',
                                'uri' => '/iem/db/abm-contacts-login',
                                'icon' => 'fa fa-database',
                                'resource' => 'admin',
                                'privilege' => 'abm',
                            ),
                        ),
                    ),
                ),
            ),
            array(
                'label' => 'Listas de Envío',
                'uri' => '#',
                'icon' => 'fa fa-list',
                'resource' => 'admin',
                'privilege' => 'abm',
                'pages' => array(
                    array(
                        'label' => 'Importar Archivo CSV',
                        'uri' => '/iem/mailinglist/abm-archive',
                        'icon' => 'fa fa-database',
                        'resource' => 'admin',
                        'privilege' => 'abm',
                    ),
                ),
            ),
            array(
                'label' => 'Envios',
                'uri' => '#',
                'icon' => 'fa fa-envelope-o fa-fw',
                'resource' => 'admin',
                'privilege' => 'abm',
                'pages' => array(
                    array(
                        'label' => 'Programación',
                        'uri' => '/iem/emailsending/abm-schedule',
                        'icon' => 'fa fa-database',
                        'resource' => 'admin',
                        'privilege' => 'abm',
                    ),
                    array(
                        'label' => 'Estado de Envíos',
                        'uri' => '#',
                        'icon' => 'fa fa-table',
                        'resource' => 'admin',
                        'privilege' => 'abm',
                        'pages' => array(
                            array(
                                'label' => 'Pendientes',
                                'uri' => '/iem/emailsending/status-pending',
                                'icon' => 'fa fa-database',
                                'resource' => 'admin',
                                'privilege' => 'abm',
                            ),
                            array(
                                'label' => 'Finalizados',
                                'uri' => '/iem/emailsending/status-finish',
                                'icon' => 'fa fa-database',
                                'resource' => 'admin',
                                'privilege' => 'abm',
                            ),
                            array(
                                'label' => 'Todos',
                                'uri' => '/iem/emailsending/status-all',
                                'icon' => 'fa fa-database',
                                'resource' => 'admin',
                                'privilege' => 'abm',
                            ),
                        )
                    ),
                )
            ),
            array(
                'label' => 'Seguimientos',
                'uri' => '#',
                'icon' => 'fa  fa-retweet fa-fw',
                'resource' => 'admin',
                'privilege' => 'abm',
                'pages' => array(
                    array(
                        'label' => 'Conversaciones',
                        'uri' => '/iem/tracing/conversations',
                        'icon' => 'fa fa-database',
                        'resource' => 'admin',
                        'privilege' => 'abm',
                    ),
                    array(
                        'label' => 'Reservas',
                        'uri' => '/iem/tracing/reservas',
                        'icon' => 'fa fa-database',
                        'resource' => 'admin',
                        'privilege' => 'abm',
                    ),
                      array(
                        'label' => 'Textos',
                        'uri' => '/iem/tracing/text',
                        'icon' => 'fa fa-database',
                        'resource' => 'admin',
                        'privilege' => 'abm',
                    ),
                      array(
                        'label' => 'Estados',
                        'uri' => '/iem/tracing/state',
                        'icon' => 'fa fa-database',
                        'resource' => 'admin',
                        'privilege' => 'abm',
                    ),
                     array(
                        'label' => 'Rebotes',
                        'uri' => '/iem/tracing/rebounds',
                        'icon' => 'fa fa-database',
                        'resource' => 'admin',
                        'privilege' => 'abm',
                    ),
                )
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    )
);
