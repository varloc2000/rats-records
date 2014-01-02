<?php

namespace Varloc\Framework;

use Symfony\Component\ClassLoader\UniversalClassLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing;

use Varloc\Framework\Component\ControllerResolver;

abstract class Kernel
{
    /**
     * Class loader
     * @var service
     */
    protected $loader;

    /**
     * Project routes
     * @var Symfony\Component\Routing\RouteCollection
     */
    protected $routes;

    /**
     * @var Symfony\Component\Routing\Matcher\UrlMatcher
     */
    protected $urlMatcher;

    /**
     * @var Symfony\Component\Routing\RequestContext
     */
    protected $context;

    /**
     * @var Varloc\Framework\Component\ControllerResolver
     */
    protected $controllerResolver;

    /**
     * Root directory of project
     * @var string
     */
    protected $baseDir;

    /**
     * debug mode 
     * @var boolean
     */
    protected $debug;

    /**$resolver
     * Create new Kernel instance
     */
    public function __construct(UniversalClassLoader $loader, $routes, $controllerResolver, $baseDir, $debug)
    {
        $this
            ->setClassLoader($loader)
            ->setRoutes($routes)
            ->setControllerResolver($controllerResolver);

        $this->baseDir = $baseDir;
        $this->debug = $debug;
    }

    /**
     * Get array of loaded namespaces and directories
     * @return array
     */
    abstract public function getNamespacesToLoad();

    /**
     * Handle Request
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request)
    {
        $templating = $this->configureTemplating();

        try {   
            /**
             * Add all attributes getted from url, to $request->attributes 
             * With help of symfony routing matcher
             */
            $request->attributes->add($this->getUrlMatcher()->match($request->getPathInfo()));
            
            // Set request attributes from route _worker option
            $this->getControllerResolver()->addRequestAttributesFromString($request);

            /** @var $controller Varloc\Framework\Controller\Controller */
            $controller = $this->getControllerResolver()->getControllerInstance($request);

            $action = $this->getControllerResolver()->getActionName($request);

            $arguments = $this->getControllerResolver()->getArguments($request, array($controller, $action));

            $controller->setTemplating($templating);

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
            $response = new Response($templating->render('wtf404.html.twig', array('exception' => $e)), 404);
        } catch (\Exception $e) {
            $response = new Response($templating->render('wtf500.html.twig', array('exception' => $e)), 500);
        }

        return $response;
    }

    /**
     * Configure and return twig templating
     * @return \Twig_Environment
     */
    protected function configureTemplating()
    {
        $templates = array($this->baseDir . '/app/views');

        foreach ($this->getNamespacesToLoad() as $namespace => $dir) {
            $templates[] = $dir . '/' . $namespace . '/' . 'views';
        }

        // Twig begins
        $loader = new \Twig_Loader_Filesystem($templates);
        $twig = new \Twig_Environment($loader, array(
            'cache'         => $this->baseDir . '/app/cache/twig',
            'auto_reload'   => $this->debug,
        ));

        // Global to check is local domain or not
        $twig->addGlobal(
            'local',
            (
                isset($_SERVER['HTTP_CLIENT_IP']) 
                || isset($_SERVER['HTTP_X_FORWARDED_FOR']) 
                || in_array(@$_SERVER['REMOTE_ADDR'], array(
                    '127.0.0.1',
                    '::1',
                ))
            ) ? true : false
        );

        return $twig;
    }

    /**
     * Load configured namespaces
     * @return void
     */
    public function loadNamespaces()
    {
        foreach ($this->getNamespacesToLoad() as $namespace => $dir) {
            $this->getClassLoader()->registerNamespace($namespace, $dir);
        }

        $this->getClassLoader()->register();
    }

    /**
     * Set service for automatic class loading
     * 
     * @param  $loader Symfony\Component\ClassLoader\UniversalClassLoader
     * @return Varloc\Framework\Kernel
     */
    public function setClassLoader($loader)
    {
        $this->loader = $loader;

        return $this;
    }

    /**
     * Get class loader
     * @return Symfony\Component\ClassLoader\UniversalClassLoader
     */
    public function getClassLoader()
    {
        return $this->loader;
    }

    /**
     * Set routes
     * 
     * @param $routes Symfony\Component\Routing\RouteCollection
     * @return Varloc\Framework\Kernel
     */
    public function setRoutes($routes)
    {
        $this->routes = $routes;

        return $this;
    }

    /**
     * Get routes
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    protected function getUrlMatcher()
    {
        if (null === $this->urlMatcher) {
            $this->urlMatcher = new UrlMatcher($this->routes, $this->getContext());
        }

        return $this->urlMatcher;
    }

    /** 
     * @return Symfony\Component\Routing\RequestContext
     */
    protected function getContext()
    {
        if (null === $this->context) {
            $this->context = new RequestContext();
        }

        return $this->context;
    }

    /** 
     * @param $resolver Varloc\Framework\Component\ControllerResolver
     */
    public function setControllerResolver(ControllerResolver $resolver)
    {
        $this->controllerResolver = $resolver;

        return $this;
    }

    /** 
     * @return Varloc\Framework\Component\ControllerResolver
     */
    public function getControllerResolver()
    {
        return $this->controllerResolver;
    }
}