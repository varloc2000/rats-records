<?php

namespace Lesson;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Varloc\Framework\Database\Connector;

use Varloc\Framework\Controller\Controller as FrameworkController;

class LessonController extends FrameworkController
{
    /**
     * Show list of marysh lessons
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function indexAction(Request $request)
    {
        $dbConnector = new Connector();
        if (false === $dbConnector->connect()) {
            throw new \Exception($dbConnector->getError());
        };
        
        $query = sprintf('SELECT * FROM marysh_lessons');
        $lessons = (array) $dbConnector->select($query);
        
        return $this->render('list.html.twig', array('lessons', $lessons));
    }
    
    /**
     * Show single lesson
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function lessonAction(Request $request)
    {
        $dbConnector = new Connector();
        if (false === $dbConnector->connect()) {
            throw new \Exception($dbConnector->getError());
        };

        $query = sprintf('SELECT * FROM marysh_lessons WHERE marysh_lessons.lesson_number = "%s"',
            $request->get('id', null)
        );
        
        $lesson = $dbConnector->selectOne($query);
        
        if ($lesson) {
            return $this->render('single.html.twig', array('lesson', $lesson));
        } else {
            return $this->render('404.html.twig', array('lesson_id', $request->get('id', null)));
        }
    }
}