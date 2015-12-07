<?php

namespace Test\Routing;

use Test\BaseTestCase;
use Zap\Routing\ControllerDispatcher;
use Zap\Routing\Route;

class RouteTest extends BaseTestCase
{
    /**
     * @covers Zap\Routing\Route::define
     */
    public function testDefine()
    {
        $this->assertInstanceOf(Route::class, Route::define('/some/uri'));
    }

    /**
     * @covers Zap\Routing\Route::getUri
     */
    public function testGetUri()
    {
        $this->assertInternalType('string', Route::define('/some/uri')->getUri());
    }

    /**
     * @covers Zap\Routing\Route::getMethod
     */
    public function testGetMethod()
    {
        $this->assertInternalType('string', Route::define('/some/uri')->getMethod());
    }

    /**
     * @covers Zap\Routing\Route::setMethod
     */
    public function testSetMethod()
    {
        $this->assertInstanceOf(Route::class,  Route::define('/some/uri')->setMethod('GET'));
    }

    /**
     * @covers Zap\Routing\Route::getCallback
     */
    public function testGetCallback()
    {
        $callback = Route::define('/some/uri')->getCallback();
        $this->assertTrue(is_null($callback) || ($callback instanceof \Closure));
    }

    /**
     * @covers Zap\Routing\Route::setCallback
     */
    public function testSetCallback()
    {
        $this->assertInstanceOf(Route::class,  Route::define('/some/uri')->setCallback(function() {}));
    }

    /**
     * @covers Zap\Routing\Route::setController
     */
    public function testSetController()
    {
        $this->assertInstanceOf(Route::class, Route::define('/some/uri')->setController('class', 'method'));
    }

    /**
     * @covers Zap\Routing\Route::getControllerDispatcher
     * @depends testSetController
     */
    public function testGetControllerDispatcher()
    {
        $currentMethod = explode('::', __METHOD__);
        $route = Route::define('/some/uri')->setController($currentMethod[0], $currentMethod[1]);
        $this->assertInstanceOf(ControllerDispatcher::class,  $route->getControllerDispatcher());
    }

    /**
     * @covers Zap\Routing\Route::getControllerClass
     */
    public function testGetControllerClass()
    {
        $this->assertInternalType('string', Route::define('/some/uri')->getControllerClass());
    }

    /**
     * @covers Zap\Routing\Route::getControllerMethod
     */
    public function testGetControllerMethod()
    {
        $this->assertInternalType('string', Route::define('/some/uri')->getControllerMethod());
    }

    /**
     * @covers Zap\Routing\Route::getUserVariables
     */
    public function testGetUserVariables()
    {
        $this->assertInternalType('array', Route::define('/some/uri')->getUserVariables());
    }

    /**
     * @covers Zap\Routing\Route::setUserVariables
     */
    public function testSetUserVariables()
    {
        $this->assertInstanceOf(Route::class, Route::define('/some/uri')->setUserVariables([]));
    }
}
