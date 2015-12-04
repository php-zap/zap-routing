<?php

namespace Zap\Routing\Interfaces;

interface IControllerDispatcher
{
    /**
     * Calls a controller based on current config
     * @param array $params
     * @return mixed
     */
    public function dispatchController(array $params = []);
}
