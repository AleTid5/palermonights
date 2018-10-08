<?php

//Autoloader de Zend Framework 2:
        const ZF2_PATH = "/var/www/palermonights/App/vendor/zendframework/zendframework/library/Zend";
require_once ZF2_PATH . '/Loader/StandardAutoloader.php';

use Zend\Loader\StandardAutoloader;

$autoLoader = new StandardAutoloader(array(
    'namespaces' => array(
        'CdiUser\Entity' => '/var/www/palermonights/App/vendor/cdi/cdiuser/src/CdiUser/Entity',
        'ZfcUser\Entity' => '/var/www/palermonights/App/vendor/zf-commons/zfc-user/src/ZfcUser/Entity',
        'BjyAuthorize\Provider\Role' => '/var/www/palermonights/App/vendor/bjyoungblood/bjy-authorize/src/BjyAuthorize/Provider/Role',
        'BjyAuthorize\Acl' => '/var/www/palermonights/App/vendor/bjyoungblood/bjy-authorize/src/BjyAuthorize/Acl',
        'Zend\Permissions\Acl\Role' => '/var/www/palermonights/App/vendor/zendframework/zendframework/library/Zend/Permissions/Acl/Role',
        'Zend\InputFilter' => '/var/www/palermonights/App/vendor/zendframework/zendframework/library/Zend/InputFilter',
        'DBAL\Entity' => '/var/www/palermonights/App/module/DBAL/src/DBAL/Entity',
        'Iem\Entity' => '/var/www/palermonights/App/module/Iem/src/Iem/Entity',
         'Iem\Service' => '/var/www/palermonights/App/module/Iem/src/Iem/Service',
         'Zend\Mail' => '/var/www/palermonights/App/vendor/zendframework/zendframework/library/Zend/Mail',
         'Zend\Stdlib' => '/var/www/palermonights/App/vendor/zendframework/zendframework/library/Zend/Stdlib',
        'Zend\Form\Annotation' => '/var/www/palermonights/App/vendor/zendframework/zendframework/library/Zend/Form/Annotation',
        'Zend' => '/var/www/palermonights/App/vendor/zendframework/zendframework/library/Zend',
        'CdiTools' => '/var/www/palermonights/App/vendor/cdi/cditools/src/CdiTools'
    ),
    'fallback_autoloader' => true,
        ));

// register our StandardAutoloader with the SPL autoloader
$autoLoader->register();


require_once 'Doctrine/Common/ClassLoader.php';

$connection = array(
    'driver' => 'pdo_mysql',
    'host' => '127.0.0.1',
    'port' => '3306',
    'user' => 'root',
    'password' => 'pD8gYKB2',
    'dbname' => 'emailmarketing',
    'mapping_types' => array('enum' => 'string')  //ojo aqui
);


$classLoader = new \Doctrine\Common\ClassLoader('Doctrine');
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('CdiUser\Entity', '/var/www/palermonights/App/vendor/cdi/cdiuser/src/CdiUser/Entity');
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\ORM');
$classLoader->register();



$classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__);
$classLoader->register();

// Gedmo Doctrine Extension classes
$classLoader = new \Doctrine\Common\ClassLoader('Gedmo', '/var/www/palermonights/App/vendor/gedmo/doctrine-extensions/lib');
$classLoader->register();



//$classLoader = new \Doctrine\Common\ClassLoader(null, '/home/security/www/App/module/DBAL/src/Entity');
//$classLoader->register();
// ensure standard doctrine annotations are registered
Doctrine\Common\Annotations\AnnotationRegistry::registerFile(
        'Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php'
);

Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
        "Zend\Form\Annotation", 
        "/var/www/palermonights/App/vendor/zendframework/zendframework/library/");

/////////////
// Second configure ORM
// globally used cache driver, in production use APC or memcached
$cache = new Doctrine\Common\Cache\ArrayCache;
// standard annotation reader
$annotationReader = new Doctrine\Common\Annotations\AnnotationReader;
$cachedAnnotationReader = new Doctrine\Common\Annotations\CachedReader(
        $annotationReader, // use reader
        $cache // and a cache driver
);



// create a driver chain for metadata reading
$driverChain = new Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain();
// load superclass metadata mapping only, into driver chain
// also registers Gedmo annotations.NOTE: you can personalize it
Gedmo\DoctrineExtensions::registerAbstractMappingIntoDriverChainORM(
        $driverChain, // our metadata driver chain, to hook into
        $cachedAnnotationReader // our cached annotation reader
);

// now we want to register our application entities,
// for that we need another metadata driver used for Entity namespace
$annotationDriver = new Doctrine\ORM\Mapping\Driver\AnnotationDriver(
        $cachedAnnotationReader, // our cached annotation reader
        array("../module/Iem/src/Iem/Entity") // paths to look in
);
// NOTE: driver for application Entity can be different, Yaml, Xml or whatever
// register annotation driver for our application Entity namespace
$driverChain->addDriver($annotationDriver, 'Iem\Entity');



// now we want to register our application entities,
// for that we need another metadata driver used for Entity namespace
$annotationDriver2 = new Doctrine\ORM\Mapping\Driver\AnnotationDriver(
        $cachedAnnotationReader, // our cached annotation reader
        array("/var/www/palermonights/App/vendor/cdi/cdiuser/src/CdiUser/Entity") // paths to look in
);
// NOTE: driver for application Entity can be different, Yaml, Xml or whatever
// register annotation driver for our application Entity namespace
$driverChain->addDriver($annotationDriver2, 'CdiUser\Entity');



// general ORM configuration
$config = new Doctrine\ORM\Configuration;

//Proxy
$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxy');
$config->setAutoGenerateProxyClasses(true); // this can be based on production config.
// Register Metadata driver
$config->setMetadataDriverImpl($driverChain);
// Cache
$config->setMetadataCacheImpl($cache);
$config->setQueryCacheImpl($cache);

/////////////
//DoctrineExtensions
$evm = new \Doctrine\Common\EventManager();
$timestampableListener = new \Gedmo\Timestampable\TimestampableListener();
$evm->addEventSubscriber($timestampableListener);

// (7) EntityManager
$em = \Doctrine\ORM\EntityManager::create($connection, $config, $evm);
?>
