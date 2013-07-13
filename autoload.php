<?php

require_once __DIR__ . '/vendor/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();

// Load varloc2000 framework at first
$loader->registerNamespace('Varloc\\Framework', __DIR__ . '/vendor_rats/varloc/framework');
$loader->register();