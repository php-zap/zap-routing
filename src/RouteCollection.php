<?php

namespace Zap\Routing;

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
     * @param Route $route
     * @return RouteCollection
     */
    public function add(Route $route) : RouteCollection
    {
        $key = $this->getKeyFromString($route->getUri());
        $this->offsetSet($key, $route);
    }

    /**
     * Removes a route from collection
     * @param Route|string $route
     * @return RouteCollection
     */
    public function remove($route) : RouteCollection
    {
        $key = $this->getKeyFromString($route instanceof Route ? $route->getUri() : $route);
        $this->offsetUnset($key);
    }

    /**
     * Finds a route by its URI
     * @param string $uri
     * @return Route
     */
    public function find(string $uri) : Route
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
     * @return Route
     */
    public function offsetGet($offset) : Route
    {
        if ($this->offsetExists($offset)) {
            return $this->routes[$offset];
        }

        return null;
    }

    /**
     * @param string $offset
     * @param Route $value
     * @return $this
     */
    public function offsetSet($offset, $value)
    {
        if (!$value instanceof Route) {
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

    /**
     * Hashes input string into an md5 hash
     * @param string $input
     * @return string
     */
    private function getKeyFromString(string $input) : string
    {
        return md5($input);
    }
}
