<?php

namespace Nano\Plugin;

class AbstractPlugin
{
    protected $serviceManager;

    public function __construct($serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }
}