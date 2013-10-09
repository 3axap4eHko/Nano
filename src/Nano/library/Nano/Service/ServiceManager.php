<?php

namespace Nano\Service;

use Nano\Behavior\ServiceContainer;
use Nano\Event\EventAwareInterface;
use Nano\Exception;
use Nano\stdCls\ArrayCollection;

class ServiceManager implements ServiceManagerInterface
{
    use ServiceContainer;

    const SERVICE_APPLICATION     = 'application';
    const SERVICE_LOADER          = 'loader';
    const SERVICE_CONFIG          = 'config';
    const SERVICE_MODULES         = 'modules';
    const SERVICE_SERVICES        = 'services';
    const SERVICE_PLUGINS         = 'plugins';
    const SERVICE_SERVICE_MANAGER = 'serviceManager';
    const SERVICE_EVENT_MANAGER   = 'eventManager';
    const SERVICE_ANNOTATION      = 'annotation';
    const SERVICE_DISPATCHER      = 'dispatcher';
    const SERVICE_ROUTER          = 'router';
    const SERVICE_REQUEST         = 'request';
    const SERVICE_ARGUMENTS       = 'arguments';
    const SERVICE_VIEW            = 'view';
    const SERVICE_DATABASE        = 'db';

    /**
     * @var \Nano\stdCls\ArrayCollection
     */
    protected $services;

    /**
     * @var \Nano\stdCls\ArrayCollection
     */
    protected $sharedServices;


    public function __construct()
    {
        $this->services = new ArrayCollection();
        $this->sharedServices = new ArrayCollection();
        SharedServiceManager::setSharedServiceManager($this);
    }

    public function set($name, $definition, $shared = false)
    {
        if ($definition instanceof \Closure)
        {
            throw new Exception('Closure not available definition. You MUST use Nano\Service\ServiceFactoryInterface');
        }
        if ($definition instanceof ServiceAwareInterface)
        {
            $definition->setServiceManager($this);
        }
        if ($shared)
        {
            $this->setShared($name, $definition);
        }
        else
        {
            $this->services->set($name, $definition);
        }

        return $this;
    }

    public function get($name, array $arguments = [])
    {
        if (!$this->services->has($name))
        {
            throw new Exception('Service name ' . $name . ' not found');
        }

        $definition = $this->services->get($name);
        if ($definition instanceof ServiceFactoryInterface)
        {
            $definition = $definition->instantiate($arguments);
        }
        elseif (is_callable($definition) && !is_object($definition))
        {
            $definition = call_user_func_array($definition, $arguments);
        }
        if ($definition instanceof ServiceAwareInterface)
        {
            $definition->setServiceManager($this);
        }
        if ($definition instanceof EventAwareInterface && $this->has(self::SERVICE_EVENT_MANAGER))
        {
            $definition->setEventManager($this->get(self::SERVICE_EVENT_MANAGER));
        }

        $this->sharedServices->set($name, $definition);

        return $definition;
    }

    public function has($name)
    {
        return $this->services->has($name);
    }

    public function setShared($name, $definition)
    {
        if ($definition instanceof \Closure)
        {
            throw new Exception('Closure not available definition');
        }

        $this->sharedServices->set($name, $definition);

        return $this;
    }

    public function getShared($name)
    {
        return $this->hasShared($name) ? $this->sharedServices->get($name) : $this->get($name);
    }

    public function hasShared($name)
    {
        return $this->sharedServices->has($name);
    }
}