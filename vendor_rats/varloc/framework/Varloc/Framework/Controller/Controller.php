<?php

namespace Varloc\Framework\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Abstract controller class to make life better
 *
 * @author  varloc2000 <varloc2000@gmail.com>
 */
abstract class Controller
{
    /**
     * Templating
     * @var 
     */
    protected $templating;

    /**
     * Set templating
     */
    public function setTemplating($templating)
    {
        $this->templating = $templating;
    }

    public function render($template, $parameters = array())
    {
        return new Response($this->templating->render($template, $parameters));
    }
}