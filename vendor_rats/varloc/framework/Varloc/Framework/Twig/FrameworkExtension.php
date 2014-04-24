<?php

namespace Varloc\Framework\Twig;

use Varloc\Framework\Kernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Varloc\Framework\Component\ControllerResolver;

class FrameworkExtension extends \Twig_Extension
{
    /**
     * @var ControllerResolver
     */
    protected $controllerResolver;

    /**
     * @var Templating
     */
    protected $templating;

    /**
     * @param ControllerResolver $controllerResolver
     * @param Templating $templating
     */
    public function __construct(ControllerResolver $controllerResolver, $templating)
    {
        $this->controllerResolver = $controllerResolver;
        $this->templating = $templating;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('render', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    /**
     * render - get controller response content 
     * @param string $controller
     * @return string
     */
    public function render($controller)
    {
        $globals = $this->templating->getGlobals();

        if (array_key_exists('request', $globals) && $globals['request'] instanceof Request) {
            $subrequest = clone $globals['request'];

            if ($globals['request']->hasPreviousSession()) {
                $subrequest->setSession($globals['request']->getSession());
            }
        } else {
            $subrequest = Request::createFromGlobals();
        }

        $subrequest->attributes->set('_worker', $controller);

        return $this->handleSubrequest($subrequest);
    }

    /**
     * Handle Request
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function handleSubrequest(Request $request)
    {
        try {
            // Set request attributes from route _worker option
            $this->controllerResolver->addRequestAttributesFromString($request);

            /** @var $controller Varloc\Framework\Controller\Controller */
            $controller = $this->controllerResolver->getControllerInstance($request);

            $action = $this->controllerResolver->getActionName($request);

            $arguments = $this->controllerResolver->getArguments($request, array($controller, $action));

            $controller->setTemplating($this->templating);

            $response = call_user_func_array(
                array(
                    $controller, 
                    $action
                ), 
                $arguments
            );

            if (!$response instanceof Response) {
                throw new \LogicException(sprintf('Controller must return Response "%s" given!', $response));
            }
        } catch (Routing\Exception\ResourceNotFoundException $e) {
            $response = new Response('', 404);
        } catch (\Exception $e) {
            throw $e;
            $response = new Response('', 500);
        }

        return $response->getContent();
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'framework';
    }
}
