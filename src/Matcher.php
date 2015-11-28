<?php

namespace Zap\Routing;

/**
 * Class Matcher
 * @package Zap\Routing
 * @author Gabor Zelei
 */
class Matcher
{
    const SEGMENT_SEPARATOR = '/';

    /**
     * Stores any variable extracted from the uri of the currently investigated route
     * Gets reset when we start to investigate a new route, or find a match
     * @var array
     */
    private $currentRouteVariables = [];

    /**
     * @var Router
     */
    private $router;

    /**
     * RouteMatcher constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Attempts to find a matching route for input $uri and $method
     * @param string $uri
     * @param string $method
     * @return Route
     */
    public function findMatch(string $uri, string $method) : Route
    {
        foreach ($this->router->getRouteCollection() as $route) {
            $this->currentRouteVariables = [];
            if ($this->isRouteAMatch($route, $uri, $method)) {
                $this->currentRouteVariables = [];
                return $route;
            }
        }

        return null;
    }

    /**
     * Return true if input uri segments match the template defined in a Routes uri segments, false otherwise
     * @param array $uriTemplateSegments
     * @param array $inputUriSegments
     * @return bool
     */
    private function compareSegmentLists(array $uriTemplateSegments, array $inputUriSegments) : bool
    {
        foreach ($inputUriSegments as $position => $inputSegment) {
            if (!$this->compareSegments($uriTemplateSegments[$position], $inputSegment)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if input segment matches template segment
     * @param string $template
     * @param string $actual
     * @return bool
     */
    private function compareSegments(string $template, string $actual) : bool
    {
        $segment = new UriTemplateSegment($template);

        if ($segment->isStatic()) {
            return ($template == $actual);
        }

        $valueWithType = $segment->getValueWithType($actual);
        $variableName = $segment->getVariableName();

        if (!empty($variableName) && (($valueWithType === 0) || ($valueWithType === 0.0) || !empty($valueWithType))) {
            $this->currentRouteVariables[$variableName] = $valueWithType;
            return true;
        }

        return false;
    }

    /**
     * Returns true if Route matches input $uri and $method, false otherwise
     * @param Route $route
     * @param string $uri
     * @param string $method
     * @return bool
     */
    private function isRouteAMatch(Route $route, string $uri, string $method) : bool
    {
        if (($route->getUri() == $uri)) {
            return true;
        }

        if ($route->getMethod() == $method) {
            $routeUriArray = $this->sliceUri($route->getUri());
            $inputUriArray = $this->sliceUri($uri);
            return $this->compareSegmentLists($routeUriArray, $inputUriArray);
        }

        return false;
    }

    /**
     * Slices a route into segments
     * @param string $uri
     * @return array
     */
    private function sliceUri(string $uri) : array
    {
        return explode(static::SEGMENT_SEPARATOR, $uri);
    }
}
