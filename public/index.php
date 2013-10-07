<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

use Nano\Loader,
    Nano\Service\DefaultServiceManager,
    Nano\Service\ServiceManager as SM,
    Nano\Application,
    Nano\Http\Request;

require_once __DIR__ . '/../src/Nano/library/Nano/Loader.php';

$loader = new Loader([
    'Nano' => __DIR__ . '/../src/Nano/library/Nano',
    'GH' => __DIR__ . '/../src/GH',
]);

$serviceManager = new DefaultServiceManager();
$serviceManager->set(SM::SERVICE_LOADER, $loader);

$a = new \Nano\Annotation\Annotation('GH\Controller\IndexController');

debug($a->reflectionClass->getDocComment());
die();
$app = new Application($serviceManager);
$app->init(__DIR__ . '/../config/application.config.php')
    ->run();

function debug()
{
    echo '<pre>';
    call_user_func_array('var_dump',func_get_args());
    die('</pre>');
}

function debugOn($condition)
{
    if ($condition)
    {
        debug(array_slice(func_get_args(),1));
    }
}