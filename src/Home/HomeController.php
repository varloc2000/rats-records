<?php

namespace Home;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Varloc\Framework\Controller\Controller as FrameworkController;
use Varloc\Framework\Database\Connector;

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
        $connection = Connector::getActiveConnection();

        $query = sprintf('SELECT * FROM marysh_lessons WHERE marysh_lessons.published = 1');
        $lessons = $connection->select($query);

        return $this->render('index.html.twig', array('lessons' => $lessons));
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