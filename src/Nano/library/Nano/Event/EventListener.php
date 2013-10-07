<?php

namespace Nano\Event;

use Nano\stdCls\ArrayCollection;

class EventListener
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var \Nano\stdCls\ArrayCollection
     */
    protected $callbacks;
    /**
     * @var \Nano\stdCls\ArrayCollection
     */
    protected $responses;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->callbacks = new ArrayCollection();
        $this->responses = [];
    }

    /**
     * @param $callback
     *
     * @return $this
     */
    public function add($callback)
    {
        $this->callbacks->append($callback);

        return $this;
    }

    /**
     * @param       $target
     * @param array $arguments
     */
    public function fire($target, array $arguments = [])
    {
        $event = new Event($this->name, $target, $arguments);
        $this->responses = $this->callbacks->map(function($callback) use ($event) {
            if ($event->isStopped()) {
                return null;
            }
            return call_user_func_array($callback, [$event]);
        });
    }

    /**
     * @return \Nano\stdCls\ArrayCollection|null
     */
    public function getResponses()
    {
        return $this->responses;
    }
}