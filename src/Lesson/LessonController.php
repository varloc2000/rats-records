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
        $connection = Connector::getActiveConnection();
        
        $query = sprintf('SELECT * FROM marysh_lessons WHERE marysh_lessons.published = 1');
        $lessons = $connection->select($query);
        
        return $this->render('list.html.twig', array('lessons' => $lessons));
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
        $connection = Connector::getActiveConnection();

        $query = sprintf('SELECT * FROM marysh_lessons WHERE marysh_lessons.number = "%s"',
            $request->get('id', null)
        );
        
        $lesson = $connection->selectOne($query);

        if ($lesson) {
            return $this->render('single.html.twig', array('lesson' => $lesson));
        } else {
            return $this->render('404.html.twig', array('lesson_id' => $request->get('id', null)));
        }
    }
}