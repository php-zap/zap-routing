<?php

namespace Zap\Routing;

use Zap\Routing\Exceptions\InvalidRouteException;

/**
 * Class ControllerCaller
 * @package Zap\Routing
 * @author Gabor Zelei
 */
class ControllerCaller
{
    private $fullClassName;

    private $methodName;

    private $controller;

    /**
     * ControllerCaller constructor.
     * @param string $fullClassName
     * @param string $methodName
     * @throws InvalidRouteException
     */
    public function __construct(string $fullClassName, string $methodName)
    {
        $this->fullClassName = $fullClassName;
        $this->$methodName = $methodName;

        try {
            $this->initController();
        } catch (InvalidRouteException $e) {
            throw $e;
        }
    }

    /**
     * Calls controller
     * @param array $params
     * @return mixed
     */
    public function callController(array $params = [])
    {
        return call_user_func_array([$this->fullClassName, $this->methodName], $params);
    }

    /**
     * Initializes controller and checks if target method is callable
     * @throws InvalidRouteException
     */
    private function initController()
    {
        if (!class_exists($this->fullClassName)) {
            throw new InvalidRouteException('Controller class does not exist: ' . $this->fullClassName);
        }

        $this->controller = new $this->fullClassName();

        if (!method_exists($this->controller, $this->methodName)) {
            throw new InvalidRouteException('Controller method does not exist: ' . $this->methodName);
        }
    }
}