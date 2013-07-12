<?php

    namespace Front;

    ini_set('display_errors', E_ALL);
    require_once __DIR__ . '/autoload.php';
    require_once __DIR__ . '/vendor/autoload.php';
    require_once __DIR__ . '/app/config/routing.php';
    require_once __DIR__ . '/app/config/database.php';
    
    // Symfony vendors
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing;
    
    // Own framework stuff
    use Varloc\Framework\Database\Connector;
    use Varloc\Framework\Component\ControllerResolver;
    
    // Configuration
    use Configurations\DatabaseConfig;
    use Configurations\RoutingConfig;

    Connector::configure(
        DatabaseConfig::getDBName(), 
        DatabaseConfig::getDBUser(), 
        DatabaseConfig::getDBPassword()
    );
    
    $request = Request::createFromGlobals();
    $routes = RoutingConfig::getProjectRoutes();
    
    $context = new Routing\RequestContext();
    $context->fromRequest($request);
    $matcher = new Routing\Matcher\UrlMatcher($routes, $context);

    try {
        /**
         * Add all attributes getted from url, to $request->attributes 
         * With help of symfony routing matcher
         */
        $request->attributes->add($matcher->match($request->getPathInfo()));
        
        /** @var $resolver Varloc\Framework\Component\ControllerResolver */
        $resolver = new ControllerResolver();

        // Set request attributes from route _worker option
        $resolver->addRequestAttributesFromString($request);

        /** @var $controller Varloc\Framework\Controller\Controller */
        $controller = $resolver->getControllerInstance($request);

        $action = $resolver->getActionName($request);

        $arguments = $resolver->getArguments($request, array($controller, $action));

        // Twig begins
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/app/views');
        $twig = new \Twig_Environment($loader, array(
            'cache'         => __DIR__ . '/app/cache/twig',
            'auto_reload'   => true,
        ));

        $controller->setTemplating($twig);

        $response = call_user_func_array(
            array(
                $controller, 
                $action
            ), 
            $arguments
        );

        $response->send();
exit;
        ob_start();
        include __DIR__ . '/app/views/layout.php';

        $response = new Response(ob_get_clean());
    } catch (Routing\Exception\ResourceNotFoundException $e) {
        ob_start();
        include __DIR__ . '/app/views/wtf404.php';

        $response = new Response(ob_get_clean(), 404);
    } catch (\Exception $e) {
        // Clear buffer contaminated by layout and start new buffer
        ob_clean();
        ob_start();
        include __DIR__ . '/app/views/wtf500.php';

        $response = new Response(ob_get_clean(), 500);
    }
     
    $response->send();
?>