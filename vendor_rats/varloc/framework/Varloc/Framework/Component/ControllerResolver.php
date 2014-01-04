<?php

namespace Varloc\Framework\Component;

use Symfony\Component\HttpFoundation\Request;

/**
 * Controller resolver to work with symfony HttpFoundation request
 *
 * @author  varloc2000 <varloc2000@gmail.com>
 */
class ControllerResolver
{
    /**
     * Set into request attributes parsed _worker route parameter,
     * which contain like "Namespace:Controller:action" string
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws LogicException
     */
    public function addRequestAttributesFromString(Request $request)
    {
        if (null === $request->attributes->get('_worker', null)) {
            throw new LogicException(sprintf(
                    'Option "_worker" for rote "%s" is required!', $request->get('_route')
            ));
        }

        $attributesArray = array();
        list(
            $attributesArray['_namespace'],
            $attributesArray['_worker'],
            $attributesArray['_action']
        ) = explode(':', $request->attributes->get('_worker'), 3);

        $request->attributes->add($attributesArray);
    }

    /**
     * Return _namespace parameter from request
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return string
     * @throws LogicException
     */
    public function getNamespaceName(Request $request)
    {
        if (null === ($namespaceName = $request->get('_namespace', null))) {
            throw new LogicException(sprintf('"_namespace" parameter not parsed yet for route "%s"', $request->get('_route', null)));
        }

        return $namespaceName;
    }

    /**
     * Return _worker parameter from request with Controller postfix
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return string
     * @throws LogicException
     */
    public function getControllerName(Request $request)
    {
        if (null === ($controllerNamePrefix = $request->get('_worker', null))) {
            throw new LogicException(sprintf('"_worker" parameter not parsed yet for route "%s"', $request->get('_route', null)));
        }

        return $controllerNamePrefix . 'Controller';
    }

    /**
     * Return full controller namespace
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return string
     * @throws LogicException
     */
    public function getFullControllerName(Request $request)
    {
        return $this->getNamespaceName($request) . '\\' . $this->getControllerName($request);
    }

    /**
     * Return new controller instance
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Varloc\Framework\Controller\Controller
     * @throws LogicException
     */
    public function getControllerInstance(Request $request)
    {
        $controller = $this->getFullControllerName($request);
        return new $controller;
    }

    /**
     * Return _action parameter from request with Action postfix
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return string
     * @throws LogicException
     */
    public function getActionName(Request $request)
    {
        if (null === ($actionNamePrefix = $request->get('_action', null))) {
            throw new LogicException(sprintf('"_action" parameter not parsed yet for route "%s"', $request->get('_route', null)));
        }

        return $actionNamePrefix . 'Action';
    }

    /**
     * Combine getNamespaceName getControllerName and getActionName methods
     * And return array for callback like:
     *   array(
     *       'Demo\SimpleController', 
     *       'simpleAction'
     *   )
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function getController(Request $request)
    {
        return array(
            $this->getNamespaceName($request) . '\\' . $this->getControllerName($request),
            $this->getActionName($request)
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param mixed $controller
     * @return array
     */
    public function getArguments(Request $request, $controller)
    {
        if (is_array($controller)) {
            $r = new \ReflectionMethod($controller[0], $controller[1]);
        } elseif (is_object($controller) && !$controller instanceof \Closure) {
            $r = new \ReflectionObject($controller);
            $r = $r->getMethod('__invoke');
        } else {
            $r = new \ReflectionFunction($controller);
        }
        
        return $this->doGetArguments($request, $controller, $r->getParameters());
    }
    
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param mixed $controller
     * @param array $parameters of ReflectionParameter elements
     * @return array
     * @throws \RuntimeException
     */
    protected function doGetArguments(Request $request, $controller, array $parameters)
    {
        $attributes = $request->attributes->all();
        $arguments = array();
        foreach ($parameters as $param) {
            if (array_key_exists($param->name, $attributes)) {
                $arguments[] = $attributes[$param->name];
            } elseif ($param->getClass() && $param->getClass()->isInstance($request)) {
                $arguments[] = $request;
            } elseif ($param->isDefaultValueAvailable()) {
                $arguments[] = $param->getDefaultValue();
            } else {
                if (is_array($controller)) {
                    $repr = sprintf('%s:%s()', get_class($controller[0]), $controller[1]);
                } elseif (is_object($controller)) {
                    $repr = get_class($controller);
                } else {
                    $repr = $controller;
                }

                throw new \RuntimeException(sprintf('Controller "%s" requires that you provide a value for the "$%s" argument (because there is no default value or because there is a non optional argument after this one).', $repr, $param->name));
            }
        }

        return $arguments;
    }

}