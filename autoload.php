<?php

require_once __DIR__.'/vendor/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php';
 
use Symfony\Component\ClassLoader\UniversalClassLoader;
 
$loader = new UniversalClassLoader();

// Load varloc2000 vendors
$loader->registerNamespace('Varloc\\DatabaseWorker', __DIR__ . '/vendor_rats/varloc/database-worker');
$loader->registerNamespace('Varloc\\Controller', __DIR__ . '/vendor_rats/varloc/controller-resolver');

$loader->registerNamespace('Cms', __DIR__ . '/src');
$loader->registerNamespace('Home', __DIR__ . '/src');
$loader->registerNamespace('Lesson', __DIR__ . '/src');

// 'Varloc\\DatabaseWorker' => $vendorDir . '/../vendor_rats/varloc/database-worker',
// 'Varloc\\Controller' => $vendorDir . '/../vendor_rats/varloc/controller-resolver',

$loader->register();