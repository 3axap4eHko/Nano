<?php

namespace Nano\Service;

class DefaultServiceManager extends ServiceManager
{
    protected $defaultServices = [
        self::SERVICE_CONFIG        => 'Nano\Config',
        self::SERVICE_EVENT_MANAGER => 'Nano\Event\EventManager',
        self::SERVICE_DISPATCHER    => 'Nano\Dispatcher\Dispatcher',
        self::SERVICE_ROUTER        => 'Nano\Router\Router',
        self::SERVICE_VIEW          => 'Nano\View\View',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->init();
    }

    protected function init()
    {
        foreach ($this->defaultServices as $serviceName => $serviceClass)
        {
            $this->set($serviceName, ServiceFactory::create($serviceClass));
        }

        return $this;
    }
}