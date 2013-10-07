<?php

namespace Nano\Event;

class Event
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var mixed
     */
    protected $target;
    /**
     * @var bool
     */
    protected $stoppable;
    /**
     * @var mixed
     */
    protected $data;
    /**
     * @var boolean
     */
    protected $stopped;

    public function __construct($name, $target, $data = [], $stoppable = true)
    {
        $this->setName($name);
        $this->setTarget($target);
        $this->setData($data);
        $this->setStoppable($stoppable);
        $this->stopped = false;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $target
     *
     * @return $this
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param mixed $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param boolean $stoppable
     *
     * @return $this
     */
    protected function setStoppable($stoppable)
    {
        $this->stoppable = $stoppable;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isStoppable()
    {
        return $this->stoppable;
    }

    /**
     * Stops the event preventing propagation
     */
    public function stop()
    {
        $this->stopped = $this->isStoppable();

        return $this;
    }

    public function isStopped()
    {
        return $this->stopped;
    }

}