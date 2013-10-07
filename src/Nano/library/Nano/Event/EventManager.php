<?php

namespace Nano\Event;


use Nano\Behavior\ServiceInjection;
use Nano\stdCls\ArrayCollection;
use Nano\Service\ServiceManagerInterface;

class EventManager implements EventManagerInterface
{
    use ServiceInjection;
    /**
     * @var \Nano\stdCls\ArrayCollection
     */
    protected $events;

    /**
     * @param ServiceManagerInterface $serviceManager
     */
    public function __construct($serviceManager = null)
    {
        $this->setServiceManager($serviceManager);
        $this->events = new ArrayCollection();
        SharedEventManager::setSharedEventManager($this);
    }

    private function register($domainName, $eventName, $callback)
    {
        if (!$this->events->has($domainName))
        {
            $this->events->set($domainName, $domain = new EventDomain($domainName));
        }
        else
        {
            $domain = $this->events->get($domainName);
        }
        /** @var EventDomain $domain */
        $domain->listen($eventName, $callback);

        return $this;
    }

    public function attach($domainName, $callback)
    {
        if (preg_match('/(\w+):(\w+)/', $domainName, $matches))
        {
            $domain = $matches[1];
            $eventName = $matches[2];
            $this->register($domain, $eventName, $callback);
        }
        elseif (is_object($callback))
        {
            $reflection = new \ReflectionClass($callback);
            foreach($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method)
            {
                $eventName = $method->getName();
                $this->register($domainName, $eventName, [$callback, $eventName]);
            }
        }

        return $this;
    }

    public function fire($domainEventName, $target = null, array $arguments = [])
    {
        if (preg_match('/(\w+):(\w+)/', $domainEventName, $matches))
        {
            $domainName = $matches[1];
            $eventName = $matches[2];
            if ($this->events->has($domainName))
            {
                /** @var EventDomain $domain */
                $domain = $this->events->get($domainName);
                $domain->fire($eventName, $target, $arguments);
            }
        }

        return $this;
    }
}