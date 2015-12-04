<?php

namespace Test\Routing;

use Test\BaseTestCase;
use Zap\Routing\RouteCollection;

class RouteCollectionTest extends BaseTestCase
{
    public function test__construct()
    {
        $this->assertInstanceOf(\ArrayAccess::class, new RouteCollection());
    }
}
