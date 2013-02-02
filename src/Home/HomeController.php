<?php

namespace Home;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController
{
    public function mainPageAction(Request $request)
    {
        return new Response(include 'views/index.php');
    }
}