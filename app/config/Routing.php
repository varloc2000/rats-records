<?php

namespace Configurations;

use Symfony\Component\Routing;
use Symfony\Component\HttpFoundation\Request;

abstract class RoutingConfig
{
    public static function getProjectRoutes()
    {
        $routes = new Routing\RouteCollection();

        $routes->add('home', new Routing\Route('/', array(
            '_worker' => 'Home:Home:mainPage'
        )));
        $routes->add('home_content', new Routing\Route('/home_content', array(
            '_worker' => 'Home:Home:mainPageContent'
        )));
        $routes->add('mail_me', new Routing\Route('/mail_me', array(
            '_worker' => 'Home:Home:mailAjax'
        )));
        
        return $routes;
    }
}
