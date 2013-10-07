<?php

namespace Nano\Service;

interface ServiceManagerInterface
{
    public function set($name, $definition, $shared = false);

    public function get($name, array $arguments = []);

    public function has($name);

    public function setShared($name, $definition);

    public function getShared($name);

    public function hasShared($name);



}