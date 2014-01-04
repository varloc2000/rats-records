<?php

namespace Configurations;

use Symfony\Component\Routing;
use Symfony\Component\HttpFoundation\Request;

abstract class RoutingConfig
{
    static public function getProjectRoutes()
    {
        $routes = new Routing\RouteCollection();

        $routes->add('home', new Routing\Route('/', array(
            '_worker' => 'Home:Home:mainPage'
        )));
        $routes->add('about', new Routing\Route('/let_me_tell_about', array(
            '_worker' => 'Home:Home:aboutPage'
        )));
        $routes->add('mail_us', new Routing\Route('/mail_us', array(
            '_worker' => 'Home:Home:mailPage'
        )));
        $routes->add('contacts', new Routing\Route('/contacts', array(
            '_worker' => 'Home:Home:contactPage'
        )));
        $routes->add('gallery', new Routing\Route('/let_me_show_you', array(
            '_worker' => 'Home:Home:galleryPage'
        )));
        $routes->add('scene', new Routing\Route('/ladys_and_gentlemans', array(
            '_worker' => 'Home:Home:sceenPage'
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