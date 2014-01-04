<?php

ini_set('display_errors', E_ALL ^ E_NOTICE);

require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/config/Database.php';
require_once __DIR__ . '/app/config/Routing.php';

require_once __DIR__ . '/app/Kernel.php';

// Symfony vendors
use Symfony\Component\HttpFoundation\Request;

// Own framework stuff
use Varloc\Framework\Database\Connector;
use Varloc\Framework\Component\ControllerResolver;

// Configuration
use Configurations\DatabaseConfig;
use Configurations\RoutingConfig;

Connector::configure(
    DatabaseConfig::getDBName(), 
    DatabaseConfig::getDBUser(), 
    DatabaseConfig::getDBPassword()
);

$kernel = new Kernel(
    $loader, 
    RoutingConfig::getProjectRoutes(), 
    new ControllerResolver(),
    __DIR__,
    true
);

// $kernel->setMenu(new Menu());
$kernel->loadNamespaces();

$request = Request::createFromGlobals();
$response = $kernel->handle($request);

$response->send();
