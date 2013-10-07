<?php

namespace Nano\Behavior;

use Nano\Event\EventManagerInterface;
use Nano\Service\ServiceAwareInterface;

trait EventInjection
{
    /**
     * @var EventManagerInterface
     */
    private $_eventManager;

    /**
     * @param \Nano\Event\EventManagerInterface $eventManager
     *
     * @return $this
     */
    public function setEventManager($eventManager)
    {
        $this->_eventManager = $eventManager;

        if ($this->_eventManager instanceof ServiceAwareInterface)
        {
            $this->_eventManager->setServiceManager($this->getServiceManager());
        }

        return $this;
    }

    /**
     * @return \Nano\Event\EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->_eventManager;
    }


}