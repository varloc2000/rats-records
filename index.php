<?php

    namespace Front;

    require_once 'vendor/autoload.php';
    require_once 'Core/db.php';

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing;

    $baseDir = __DIR__;

    $request = Request::createFromGlobals();
    $routes = include __DIR__.'/Core/routing.php';
     
    $context = new Routing\RequestContext();
    $context->fromRequest($request);
    $matcher = new Routing\Matcher\UrlMatcher($routes, $context);

    try {
        extract($matcher->match($request->getPathInfo()), EXTR_SKIP);
        ob_start();
        include __DIR__.'/app/views/layout.php';
     
        $response = new Response(ob_get_clean());
    } catch (Routing\Exception\ResourceNotFoundException $e) {
        ob_start();
        include __DIR__.'/app/views/wtf404.html';

        $response = new Response(ob_get_clean(), 404);
    } catch (Exception $e) {
        $response = new Response('Internal server error!', 500);
    }
     
    $response->send();
?>