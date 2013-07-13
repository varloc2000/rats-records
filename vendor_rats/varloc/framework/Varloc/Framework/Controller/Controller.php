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
     * @var \ReflectionObject of current controller
     */
    protected $reflected;

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

    /**
     * Return symfony http Response with rendered template
     * 
     * @param  string $template
     * @param  array  $parameters
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function render($template, $parameters = array())
    {
        return new Response($this->templating->render($template, $parameters));
    }

    /**
     * Gets the Controller directory path.
     *
     * @return string The Controller absolute path
     */
    public function getPath()
    {
        if (null === $this->reflected) {
            $this->reflected = new \ReflectionObject($this);
        }

        return dirname($this->reflected->getFileName());
    }

    /**
     * Gets the Controller namespace.
     *
     * @return string The Controller namespace
     */
    public function getNamespace()
    {
        if (null === $this->reflected) {
            $this->reflected = new \ReflectionObject($this);
        }

        return $this->reflected->getNamespaceName();
    }
}