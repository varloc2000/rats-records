<?php

namespace Home;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController
{
    /**
     * Render home page of project
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mainPageAction(Request $request)
    {
        return new Response(include('views/index.php'));
    }

    /**
     * Render scene page of project
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function scenePageAction(Request $request)
    {
        return new Response(include('views/scene.php'));
    }

    /**
     * Render about page
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutPageAction(Request $request)
    {
        return new Response(include('views/about.php'));
    }
}