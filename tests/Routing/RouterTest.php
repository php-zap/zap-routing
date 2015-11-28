<?php

namespace Test\Routing;

use Test\BaseTestCase;
use Zap\Routing\Route;
use Zap\Routing\RouteCollection;
use Zap\Routing\Router;

class RouterTest extends BaseTestCase
{
    /**
     * @covers Zap\Routing\Router::get
     */
    public function testGet()
    {
        $this->assertInstanceOf(Router::class, Router::get());
    }

    /**
     * @covers Zap\Routing\Router::addRoute
     */
    public function testAddRoute()
    {
        $this->assertInstanceOf(Router::class, Router::get()->addRoute(new Route('/some/uri')));
    }

    /**
     * @covers Zap\Routing\Router::getRouteCollection
     */
    public function testGetRouteCollection()
    {
        $this->assertInstanceOf(RouteCollection::class, Router::get()->getRouteCollection());
    }

    /**
     * @covers Zap\Routing\Router::setRouteCollection
     */
    public function testSetRouteCollection()
    {
        $collection = new RouteCollection();
        $this->assertInstanceOf(Router::class, Router::get()->setRouteCollection($collection));
    }

    /**
     * @covers Zap\Routing\Router::setRouteCollection
     */
    public function testFetch()
    {
        $result = Router::get()->fetch('/some/uri', 'GET');
        $this->assertNull($result);                 // @todo extend test case, once method can return Zap\Http\Response
    }
}
