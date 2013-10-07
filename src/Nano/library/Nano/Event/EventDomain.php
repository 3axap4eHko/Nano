<?php

namespace Nano\Event;

use Nano\stdCls\ArrayCollection;

class EventDomain
{
    protected $name;
    /**
     * @var \Nano\stdCls\ArrayCollection
     */
    protected $listeners;

    public function __construct($name)
    {
        $this->name = $name;
        $this->listeners = new ArrayCollection();
    }

    public function listen($eventName, $callback)
    {
        if (!$this->listeners->has($eventName))
        {
            $this->listeners->set($eventName, $listener = new EventListener($eventName));
        }
        else
        {
            $listener = $this->listeners->get($eventName);
        }
        /** @var EventListener $listener */
        $listener->add($callback);

        return $this;
    }

    public function fire($eventName, $target, $arguments)
    {
        if ($this->listeners->has($eventName))
        {
            /** @var EventListener $listener */
            $listener = $this->listeners->get($eventName);
            $listener->fire($target, $arguments);
        }
    }


}