<?php

namespace Zap\Routing;

use Zap\Routing\Exceptions\InvalidRouteException;
use Zap\Routing\Interfaces\IMatcher;
use Zap\Routing\Interfaces\IRoute;

/**
 * Class Router
 * @package Zap\Routing
 * @author Gabor Zelei
 */
class Router
{
    /**
     * @var Router
     */
    private static $instance;

    /**
     * @var IMatcher
     */
    private $matcher;

    /**
     * @var RouteCollection
     */
    private $routes;

    /**
     * Router singleton constructor.
     */
    private function __construct()
    {
        $this->matcher = new Matcher($this);
        $this->routes = new RouteCollection();
    }

    /**
     * Neuter clone method
     */
    private function __clone()
    {
    }

    /**
     * Singleton instance accessor
     * @return Router
     */
    public static function get() : Router
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Adds a route to the collection this router knows about
     * @param IRoute $route
     * @return Router
     */
    public function addRoute(IRoute $route) : Router
    {
        $this->routes->add($route);
        return $this;
    }

    /**
     * Attempts to find a route based on current uri and request method
     * @param string $uri
     * @param string $method
     * @return mixed|null
     */
    public function fetch(\string $uri, \string $method)
    {
        try {
            $route = $this->matcher->findMatch($uri, $method);

            if (is_null($route)) {
                throw new InvalidRouteException(sprintf('Invalid route definition: %s %s', strtoupper($method), $uri));
            }

            return $route->process();
        } catch (InvalidRouteException $e) {
            // Show 404 here
            return null;
        } catch (\Exception $e) {
            // Show 500 here
            return null;
        }
    }

    /**
     * Returns route collection
     * @return RouteCollection
     */
    public function getRouteCollection() : RouteCollection
    {
        return $this->routes;
    }

    /**
     * Sets a new collection of routes to router
     * @param RouteCollection $routes
     * @return Router
     */
    public function setRouteCollection(RouteCollection $routes) : Router
    {
        $this->routes = $routes;
        return $this;
    }
}
