<?php
// print_r(apache_get_modules());
// echo "<pre>"; print_r($_SERVER); die;
// $_SERVER["REQUEST_URI"] = str_replace("/phalt/","/",$_SERVER["REQUEST_URI"]);
// $_GET["_url"] = "/";
include('../vendor/autoload.php');
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
use Phalcon\Session\Manager;
use Phalcon\Http\Response\Cookies;
use Phalcon\Config\ConfigFactory;
use Phalcon\Logger\AdapterFactory;
use Phalcon\Logger\LoggerFactory;
use Phalcon\Logger\Adapter\Stream;
use Phalcon\Events\Manager as EventsManager;
use App\Locale\Locale;



$config = new Config([]);

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
    ]
);

$loader->registerNamespaces(
    [
        'App\Components' => APP_PATH . '/components',
        'App\Listeners' => APP_PATH . '/listeners',
        'App\Locale' => APP_PATH . '/locale',

    ]
);

$loader->register();


$container = new FactoryDefault();
$application = new Application($container);

$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);
$eventsManager = new eventsManager ;

$eventsManager->attach(
    'application:beforeHandleRequest',
     new App\Listeners\NotificationsListeners()
      );

$container->set(
    'locale', 
    (new Locale())->getTranslator());


$container->set(
    'eventsManager',
    $eventsManager
);

$application->setEventsManager($eventsManager);


$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);




$container->set(
    'db',
    function () {
        $config = $this->getConfig();
        return new Mysql(
            [
                'host'     => $config->path('db.host'),
                'username' => $config->path('db.username'),
                'password' => $config->path('db.password'),
                'dbname'   => $config->path('db.dbname'),
                ]
        );
    }
);


$container->set( 
    'mylogs',
    function() {
        $adapters = [
            "main"  => new \Phalcon\Logger\Adapter\Stream("../storage/log/main.log")
        ];
        $adapterFactory = new AdapterFactory();
        $loggerFactory  = new LoggerFactory($adapterFactory);
        
        return $loggerFactory->newInstance('prod-logger', $adapters);
    }, 
    true
 );


$container->set(
    'session',
    function () {
        $session = new Manager();
        $files = new Stream(
            [
                'savePath' => '/tmp',
            ]
        );

        $session
            ->setAdapter($files)
            ->start();

        return $session;
    },
    true
);


// $container->set(
//     'escaper',
//     function () {
//         return new Escaper();
//     }
// );

$container->set( 
    'config',
    function() {
    $fileName = '../app/etc/config.php';
    $factory  = new ConfigFactory();
    return $factory->newInstance('php', $fileName);
    }, 
    true
 );

$container->set( 
    "cookies", function () { 
       $cookies = new Cookies();  
       $cookies->useEncryption(false);  
       return $cookies; 
    } 
 );

$container->set(
    'mongo',
    function () {
        $mongo = new MongoClient();

        return $mongo->selectDB('phalt');
    },
    true
);

try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}