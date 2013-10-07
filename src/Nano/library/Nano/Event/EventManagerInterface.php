<?php

namespace Nano\Event;

use Nano\Service\ServiceAwareInterface;

interface EventManagerInterface extends ServiceAwareInterface
{
    public function attach($domainName, $callback);

    public function fire($domainEventName, $target = null, array $arguments = []);
}