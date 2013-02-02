<?php

    namespace Front;

    require_once __DIR__ . '/autoload.php';
    require_once 'vendor/autoload.php';

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing;
    
    use Varloc\Controller\ControllerResolver;
    use Varloc\DatabaseWorker\Connector;
    
    Connector::configure('ratsreco_storage', 'root', '80177413');
    
    $request = Request::createFromGlobals();
    $routes = include __DIR__.'/app/config/routing.php';
     
    $context = new Routing\RequestContext();
    $context->fromRequest($request);
    $matcher = new Routing\Matcher\UrlMatcher($routes, $context);

    try {
        /**
         * Add all attributes getted from url, to $request->attributes 
         * With help of symfony routing matcher
         */
        $request->attributes->add($matcher->match($request->getPathInfo()));
        
        $resolver = new ControllerResolver();
        // Set request attributes from route _worker option
        $resolver->addRequestAttributesFromString($request);
        
        $controller = $resolver->getController($request);
        $arguments = $resolver->getArguments($request, $controller);
        
        ob_start();
        include __DIR__ . '/app/views/layout.php';

        $response = new Response(ob_get_clean());
    } catch (Routing\Exception\ResourceNotFoundException $e) {
        ob_start();
        include __DIR__ . '/app/views/wtf404.php';

        $response = new Response(ob_get_clean(), 404);
    } catch (\Exception $e) {
        ob_start();
        include __DIR__ . '/app/views/wtf500.php';

        $response = new Response(ob_get_clean(), 500);
    }
     
    $response->send();
?>