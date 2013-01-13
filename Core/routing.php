<?php

use Symfony\Component\Routing;
 
$routes = new Routing\RouteCollection();

$routes->add('lesson', new Routing\Route('/lessons/{id}', array('id' => 'home')));
$routes->add('home', new Routing\Route('/'));
 
return $routes;