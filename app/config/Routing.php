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
        $routes->add('mail_us', new Routing\Route('/mail_us', array(
            '_worker' => 'Home:Home:mailBlock'
        )));

        /*
         * Lessons part routes
         */
        $routes->add('lesson_list', new Routing\Route('/lessons', array(
            '_worker' => 'Lesson:Lesson:index'
        )));
        $routes->add('lesson', new Routing\Route('/lessons/{id}', array(
            '_worker' => 'Lesson:Lesson:lesson'
        )));
        
        return $routes;
    }
}
