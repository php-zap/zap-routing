<?php

namespace Zap\Routing;

use Zap\Routing\Exceptions\InvalidRouteException;

/**
 * Class Route
 * @package Zap\Routing
 * @author Gabor Zelei
 */
class Route
{
    /**
     * Default method @todo: remove once enum is available in Zap\Http
     */
    const GET = 'GET';

    /**
     * @var string
     */
    private $uri;

    /**
     * @var string
     */
    private $method = self::GET;

    /**
     * @var \Closure
     */
    private $callback;

    /**
     * @var string
     */
    private $controllerClass;

    /**
     * @var string
     */
    private $controllerMethod;

    /**
     * Route constructor.
     * @param string $uri
     */
    public function __construct(string $uri)
    {
        $this->uri = '/' . trim($uri, '/');
    }

    /**
     * Shorthand method for instance creation for chaining
     * @param string $uri
     * @return Route
     */
    public static function define(string $uri) : Route
    {
        return new static($uri);
    }

    /**
     * Gets route URI
     * @return string
     */
    public function getUri() : string
    {
        return $this->uri;
    }

    /**
     * Gets HTTP request method for route
     * @return string
     */
    public function getMethod() : string
    {
        return $this->method;
    }

    /**
     * Sets HTTP request method for route
     * @param string $method
     * @return Route
     */
    public function setMethod(string $method) : Route
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Returns callback defined for this route
     * @return \Closure
     */
    public function getCallback() : \Closure
    {
        return $this->callback;
    }

    /**
     * Sets a callback to this route
     * Callback will be executed when the route is dispatched
     * Having a valid controller class and method set for this route will override callback settings set here
     * @param \Closure $callback
     * @return Route
     */
    public function setCallback(\Closure $callback) : Route
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * Gets ControllerCaller for this route
     * @return ControllerCaller
     */
    public function getController() : ControllerCaller
    {
        return new ControllerCaller($this->controllerClass, $this->controllerMethod);
    }

    /**
     * Sets controller class and method name for this route
     * @param string $fullClassName
     * @param string $methodName
     * @return Route
     */
    public function setController(string $fullClassName, string $methodName) : Route
    {
        $this->controllerClass = $fullClassName;
        $this->controllerMethod = $methodName;
        return $this;
    }

    /**
     * Executes route
     * @return mixed                    @todo Change to Zap\Http\Response|array|bool|mixed
     * @throws InvalidRouteException
     */
    public function process()
    {
        try {
            if (!empty($this->controllerClass) && !empty($this->controllerMethod)) {
                // Attempt to call controller
                return $this->getController()->callController();
            } elseif (is_callable($this->callback)) {
                // Call user callback in an empty context
                return $this->callback->call(new \stdClass());
            } else {
                throw new InvalidRouteException('No execution plan defined for route');
            }
        } catch (InvalidRouteException $e) {
            return $e;                  // @todo change to proper Zap\Http\Response instance
        }
    }
}
