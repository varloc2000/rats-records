<?php

namespace Lesson;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Varloc\DatabaseWorker\Connector;

class LessonController
{
    public function indexAction(Request $request)
    {
        $dbConnector = new Connector();
        if (false === $dbConnector->connect()) {
            throw new \Exception($dbConnector->getError());
        };
        
        $query = sprintf('SELECT * FROM marysh_lessons');
        $lessons = $dbConnector->select($query);
        
        if ($lessons) {
            return new Response(include 'views/list.php');
        } else {
            $response->setContent('no');
        }
    }
    
    public function lessonAction(Request $request)
    {
        $dbConnector = new Connector();
        if (false === $dbConnector->connect()) {
            throw new \Exception($dbConnector->getError());
        };
        $query = sprintf('SELECT * FROM marysh_lessons WHERE marysh_lessons.lesson_number = "%s"',
            $request->get('id', null)
        );
        
        $lesson = $dbConnector->select($query);
        
        if ($lesson) {
            return new Response(include 'views/single.php');
        } else {
            return new Response(include 'views/404.html');
        }
    }
}