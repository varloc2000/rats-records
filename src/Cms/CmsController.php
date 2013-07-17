<?php

namespace Lesson;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Varloc\DatabaseWorker\Connector;

use Varloc\Framework\Controller\Controller as FrameworkController;

class CmsController extends FrameworkController
{
    public function indexAction(Request $request)
    {
    }
}