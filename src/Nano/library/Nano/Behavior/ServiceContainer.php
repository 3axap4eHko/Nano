<?php

namespace Nano\Behavior;

use Nano\Service\ServiceAwareInterface;
use Nano\Config;
use Nano\Event\EventManagerInterface;
use Nano\Dispatcher\DispatcherInterface;
use Nano\Router\RouterInterface;

/**
 * Class ServiceContainer
 * @package Nano\Behavior
 *
 * @property Config                $config
 * @property EventManagerInterface $eventManager
 * @property DispatcherInterface   $dispatcher
 * @property RouterInterface       $router
 */
trait ServiceContainer
{
    public function __get($name)
    {
        if ($this instanceof ServiceAwareInterface && $this->getServiceManager()->has($name))
        {
            return $this->getServiceManager()->getShared($name);
        }
    }
}