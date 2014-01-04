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
        $subrequest = Request::createFromGlobals();
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
            $response = new Response('', 500);
        }

        return $response->getContent();
    }

    /**
     * createSubRequest - stolen from Symfony\Component\HttpKernel\Fragment\InlineFragmentRenderer
     * @param string $uri
     * @param Request $request
     * @return Request
     */
    protected function createSubRequest($uri, Request $request)
    {
        $cookies = $request->cookies->all();
        $server = $request->server->all();

        // Override the arguments to emulate a sub-request.
        // Sub-request object will point to localhost as client ip and real client ip
        // will be included into trusted header for client ip
        try {
            $trustedHeaderName = Request::getTrustedHeaderName(Request::HEADER_CLIENT_IP);
            $currentXForwardedFor = $request->headers->get($trustedHeaderName, '');

            $server['HTTP_'.$trustedHeaderName] = ($currentXForwardedFor ? $currentXForwardedFor.', ' : '').$request->getClientIp();
        } catch (\InvalidArgumentException $e) {
            // Do nothing
        }

        $server['REMOTE_ADDR'] = '127.0.0.1';

        $subRequest = $request::create($uri, 'get', array(), $cookies, array(), $server);
        if ($request->headers->has('Surrogate-Capability')) {
            $subRequest->headers->set('Surrogate-Capability', $request->headers->get('Surrogate-Capability'));
        }

        if ($session = $request->getSession()) {
            $subRequest->setSession($session);
        }

        return $subRequest;
    }

    /**
     * Generates a fragment URI for a given controller.
     *
     * @param ControllerReference  $reference A ControllerReference instance
     * @param Request              $request    A Request instance
     *
     * @return string A fragment URI
     */
    protected function generateFragmentUri(ControllerReference $reference, Request $request)
    {
        if (!isset($reference->attributes['_format'])) {
            $reference->attributes['_format'] = $request->getRequestFormat();
        }

        $reference->attributes['_controller'] = $reference->controller;

        $reference->query['_path'] = http_build_query($reference->attributes, '', '&');

        return $request->getUriForPath($this->fragmentPath.'?'.http_build_query($reference->query, '', '&'));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'framework';
    }
}
