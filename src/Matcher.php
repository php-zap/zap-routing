<?php

namespace Zap\Routing;
use Zap\Routing\UriSegment\Comparator;

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
    private $routeVariablesBuffer = [];

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
            if ($this->isRouteAMatch($route, $uri, $method)) {
                $route->setUserVariables($this->routeVariablesBuffer);
                return $route;
            }
        }

        return null;
    }

    /**
     * Returns true if input URI segments match the template defined in a Route's uri segments, false otherwise
     * Also extracts user variables passed for Route from input URI segments
     * @param array $uriTemplateSegments
     * @param array $inputUriSegments
     * @return bool
     */
    private function compareSegmentLists(array $uriTemplateSegments, array $inputUriSegments) : bool
    {
        $this->routeVariablesBuffer = [];

        foreach ($inputUriSegments as $position => $inputSegment) {
            $result = Comparator::create($uriTemplateSegments[$position], $inputSegment)->compare();

            if (!$result->isSuccess()) {
                return false;
            }

            $segmentName = $result->getSegmentName();

            if (!empty($segmentName)) {
                $this->routeVariablesBuffer[$segmentName] = $result->getSegmentValue();
            }
        }

        return true;
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
        if ($route->getMethod() == $method) {

            if (($route->getUri() == $uri)) {
                return true;
            }

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
