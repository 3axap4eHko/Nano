<?php

namespace Nano\Dispatcher;

use Nano\Event\EventAwareInterface;
use Nano\Service\ServiceAwareInterface;

interface DispatcherInterface extends ServiceAwareInterface, EventAwareInterface
{
    public function dispatch($arguments);
}