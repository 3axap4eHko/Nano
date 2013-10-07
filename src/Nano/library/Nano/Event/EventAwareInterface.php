<?php

namespace Nano\Event;

interface EventAwareInterface
{
    /**
     * @param \Nano\Event\EventManagerInterface $eventManager
     *
     * @return $this
     */
    public function setEventManager($eventManager);
    /**
     * @return \Nano\Event\EventManagerInterface
     */
    public function getEventManager();
}