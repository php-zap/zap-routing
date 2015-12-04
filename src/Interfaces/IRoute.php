<?php

namespace Zap\Routing\Interfaces;

use Zap\Routing\Exceptions\InvalidRouteException;

interface IRoute
{
    public function getUri() : string;

    /**
     * Gets HTTP request method for route
     * @return string
     */
    public function getMethod() : \string;

    /**
     * Sets HTTP request method for route
     * @param string $method
     * @return IRoute
     */
    public function setMethod(\string $method) : IRoute;

    /**
     * Returns callback defined for this route
     * @return null|\Closure
     */
    public function getCallback();

    /**
     * Sets a callback to this route
     * Callback will be executed when the route is dispatched
     * Having a valid controller class and method set for this route will override callback settings set here
     * @param \Closure $callback
     * @return IRoute
     */
    public function setCallback(\Closure $callback) : IRoute;

    /**
     * Gets ControllerCaller for this route
     * @return IControllerDispatcher
     */
    public function getControllerDispatcher() : IControllerDispatcher;

    /**
     * Sets controller class and method name for this route
     * @param string $fullClassName
     * @param string $methodName
     * @return IRoute
     */
    public function setController(\string $fullClassName, \string $methodName) : IRoute;

    /**
     * Returns full namespaces classpath for any configured controller class for this route
     * @return string
     */
    public function getControllerClass() : \string;

    /**
     * Returns method to call on controller class when processing route
     * @return string
     */
    public function getControllerMethod() : \string;

    /**
     * Returns a list of user variables to pass to controller/callback when route is processed
     * @return array
     */
    public function getUserVariables() : array;

    /**
     * Stores a list of user variables to pass to controller/callback when route is processed
     * @param array $userVariables
     * @return IRoute
     */
    public function setUserVariables(array $userVariables = []) : IRoute;

    /**
     * Executes route
     * @return mixed                    @todo Change to Zap\Http\Response|array|bool|mixed
     * @throws InvalidRouteException
     */
    public function process();

    /**
     * Registers route against router
     * @return IRoute
     */
    public function register() : IRoute;
}
