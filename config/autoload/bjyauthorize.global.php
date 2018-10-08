<?php

return array('bjyauthorize' => array(
        'default_role' => 'guest',
        // Using the authentication identity provider, which basically reads the roles from the auth service's identity
        'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',
        'role_providers' => array(
            // using an object repository (entity repository) to load all roles into our ACL
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                'object_manager' => 'doctrine.entitymanager.orm_default',
                'role_entity_class' => 'CdiUser\Entity\Role',
            ),
        ),
        // resource providers provide a list of resources that will be tracked
        // in the ACL. like roles, they can be hierarchical
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'guest' => array(),
                'user' => array(),
                'admin' => array(),
                'moderator' => array(),
                'supervisor' => array(),
                'zfcuseradmin' => array(),
            ),
        ),
        /* rules can be specified here with the format:
         * array(roles (array), resource, [privilege (array|string), assertion])
         * assertions will be loaded using the service manager and must implement
         * Zend\Acl\Assertion\AssertionInterface.
         * *if you use assertions, define them using the service manager!*
         */
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    // allow guests and users (and admins, through inheritance)
                    // the "wear" privilege on the resource "pants"}
                    array(array('user'), 'user', array('show', 'abm', 'report')),
                    array(array('admin', 'supervisor'), 'admin', array('show', 'abm', 'report')),
                    array(array('moderator'), 'moderator', array('follow')),
                    array(array('supervisor'), 'supervisor', array('follow')),
                ),
                // Don't mix allow/deny rules if you are using role inheritance.
                // There are some weird bugs.
                'deny' => array(
                    array(array('guest', 'user'), 'zfcuseradmin', 'list')
                ),
            ),
        ),
        /* Currently, only controller and route guards exist
         *
         * Consider enabling either the controller or the route guard depending on your needs.
         */
        'guards' => array(
            /* If this guard is specified here (i.e. it is enabled), it will block
             * access to all controllers and actions unless they are specified here.
             * You may omit the 'action' index to allow access to the entire controller
             */

            'BjyAuthorize\Guard\Controller' => array(
                array('controller' => 'Application\Controller\Index', 'action' => 'index', 'roles' => array('guest')),
                array('controller' => 'zfcuser', 'roles' => array()),
                array('controller' => 'zfcuseradmin', 'roles' => array('admin')),
                array('controller' => 'Iem\Controller\Iabm', 'roles' => array('supervisor', 'admin')),
                 array('controller' => 'Iem\Controller\Resources', 'roles' => array('supervisor', 'admin')),
                 array('controller' => 'Iem\Controller\Templates', 'roles' => array('supervisor', 'admin')),
                 array('controller' => 'Iem\Controller\Db', 'roles' => array('supervisor', 'admin')),
                 array('controller' => 'Iem\Controller\Mailinglist', 'roles' => array('supervisor', 'admin')),
                 array('controller' => 'Iem\Controller\Emailsending', 'roles' => array('supervisor', 'admin')),
                  array('controller' => 'Iem\Controller\Index', 'roles' => array('supervisor', 'admin')),
                  array('controller' => 'Iem\Controller\Tracing', 'roles' => array('supervisor', 'admin')),
            )
        // Below is the default index action used by the ZendSkeletonApplication
        // array('controller' => 'Application\Controller\Index', 'roles' => array('guest', 'user')),
        ),
    )
);
