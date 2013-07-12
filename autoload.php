<?php

require_once __DIR__ . '/vendor/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();

// Load varloc2000 framework
$loader->registerNamespace('Varloc\\Framework', __DIR__ . '/vendor_rats/varloc/framework');

$loader->registerNamespace('Cms', __DIR__ . '/src');
$loader->registerNamespace('Home', __DIR__ . '/src');
$loader->registerNamespace('Lesson', __DIR__ . '/src');

$loader->register();