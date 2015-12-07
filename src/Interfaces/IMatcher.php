<?php

namespace Zap\Routing\Interfaces;

interface IMatcher
{
    /**
     * Attempts to find a matching route for input $uri and $method
     * @param string $uri
     * @param string $method
     * @return IRoute
     */
    public function findMatch(\string $uri, \string $method);
}
