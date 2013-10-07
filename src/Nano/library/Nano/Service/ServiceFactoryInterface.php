<?php

namespace Nano\Service;


interface ServiceFactoryInterface extends ServiceAwareInterface
{
    public function instantiate(array $arguments = []);
}