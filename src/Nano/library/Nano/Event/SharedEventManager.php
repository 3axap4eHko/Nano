<?php

namespace Nano\Event;

use Nano\Event\EventManagerInterface;

class SharedEventManager
{
    /**
     * @var EventManagerInterface
     */
    private static $sharedEventManager;

    /**
     * @param \Nano\Event\EventManagerInterface $sharedEventManager
     */
    public static function setSharedEventManager($sharedEventManager)
    {
        self::$sharedEventManager = $sharedEventManager;
    }

    /**
     * @return \Nano\Event\EventManagerInterface
     */
    public static function getSharedEventManager()
    {
        return self::$sharedEventManager;
    }
}