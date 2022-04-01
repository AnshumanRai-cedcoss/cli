<?php


use Exception;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Session\Manager;
use Phalcon\Http\Response\Cookies;
use Phalcon\Config\ConfigFactory;
use Phalcon\Logger\AdapterFactory;
use Phalcon\Logger\LoggerFactory;
use Phalcon\Logger\Adapter\Stream;
use Phalcon\Events\Manager as EventsManager;
use App\Locale\Locale;
use Phalcon\Config;
use Phalcon\Cli\Console;
use Phalcon\Cli\Dispatcher;
use Phalcon\Di\FactoryDefault\Cli as CliDI;
use Phalcon\Exception as PhalconException;
use Phalcon\Loader;
use Throwable;

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
    ]
);


$loader->registerNamespaces(
    [
       'App\Console' => APP_PATH.'/console',
       'App\Controllers' => APP_PATH.'/controllers',
    ]
);
$loader->register();

$container  = new CliDI();
$dispatcher = new Dispatcher();

$dispatcher->setDefaultNamespace('App\Console');
$container->setShared('dispatcher', $dispatcher);


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


$console = new Console($container);

$arguments = [];
foreach ($argv as $k => $arg) {
    if ($k === 1) {
        $arguments['task'] = $arg;
    } elseif ($k === 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        $arguments['params'][] = $arg;
    }
}

try {
    $console->handle($arguments);
} catch (PhalconException $e) {
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    exit(1);
} catch (Throwable $throwable) {
    fwrite(STDERR, $throwable->getMessage() . PHP_EOL);
    exit(1);
} catch (Exception $exception) {
    fwrite(STDERR, $exception->getMessage() . PHP_EOL);
    exit(1);
}