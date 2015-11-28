<?php

namespace Zap\Routing;

use Zap\Routing\Exceptions\InvalidRouteException;

/**
 * Class ControllerCaller
 * @package Zap\Routing
 * @author Gabor Zelei
 */
class ControllerDispatcher
{
    /**
     * @var Route
     */
    private $route;

    /**
     * @var mixed       // @todo make sure this is actually Zap\Common\Interfaces\IController once IF is available
     */
    private $controller;

    /**
     * ControllerCaller constructor.
     * @param Route $route
     * @throws InvalidRouteException
     */
    public function __construct(Route $route)
    {
        $this->route = $route;

        try {
            $this->initController();
        } catch (InvalidRouteException $e) {
            throw $e;
        }
    }

    /**
     * Calls a controller based on current config
     * @param array $params
     * @return mixed
     */
    public function dispatchController(array $params = [])
    {
        return call_user_func_array([$this->route->getControllerClass(), $this->route->getControllerMethod()], $params);
    }

    /**
     * Initializes controller and checks if target method is callable
     * @throws InvalidRouteException
     */
    private function initController()
    {
        $fullClassName = $this->route->getControllerClass();
        $methodName = $this->route->getControllerMethod();

        if (!class_exists($fullClassName)) {
            throw new InvalidRouteException('Controller class does not exist: ' . $fullClassName);
        }

        $this->controller = new $fullClassName();

        if (!method_exists($this->controller, $methodName)) {
            throw new InvalidRouteException('Controller method does not exist: ' . $methodName);
        }
    }
}
