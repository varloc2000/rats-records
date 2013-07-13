<?php

namespace Home;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Varloc\Framework\Controller\Controller as FrameworkController;

class HomeController extends FrameworkController
{
    /**
     * Render home page of project
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mainPageAction(Request $request)
    {
        return $this->render('index.html.twig');
    }

    /**
     * Render scene page of project
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sceenPageAction(Request $request)
    {
        return $this->render('sceen.html.twig');
    }

    /**
     * Render about page
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutPageAction(Request $request)
    {
        return $this->render('about.html.twig');
    }
}