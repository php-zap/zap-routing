<?php

namespace Zap\Routing;

use Zap\Routing\Interfaces\IRoute;

/**
 * Class RouteCollection
 * @package Zap\Routing
 * @author Gabor Zelei
 */
class RouteCollection implements \ArrayAccess
{
    /**
     * @var array
     */
    private $routes = [];

    /**
     * Adds a route to collection
     * @param IRoute $route
     * @return RouteCollection
     */
    public function add(IRoute $route) : RouteCollection
    {
        $this->offsetSet($route->getUri(), $route);
        return $this;
    }

    /**
     * Removes a route from collection
     * @param IRoute|string $route
     * @return RouteCollection
     */
    public function remove($route) : RouteCollection
    {
        $key = $route instanceof Route ? $route->getUri() : $route;
        $this->offsetUnset($key);
        return $this;
    }

    /**
     * Finds a route by its URI
     * @param string $uri
     * @return IRoute
     */
    public function find(\string $uri) : IRoute
    {
        return $this->offsetGet($uri);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->routes[$offset]);
    }

    /**
     * @param string $offset
     * @return IRoute
     */
    public function offsetGet($offset) : IRoute
    {
        if ($this->offsetExists($offset)) {
            return $this->routes[$offset];
        }

        return null;
    }

    /**
     * @param string $offset
     * @param IRoute $value
     * @return $this
     */
    public function offsetSet($offset, $value)
    {
        if (!$value instanceof IRoute) {
            throw new \InvalidArgumentException();
        }

        $this->routes[$offset] = $value;
        return $this;
    }

    /**
     * @param string $offset
     * @return $this
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->routes[$offset]);
        }

        return $this;
    }
}
