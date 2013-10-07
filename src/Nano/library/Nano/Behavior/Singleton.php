<?php

namespace Nano\Behavior;

trait Singleton
{
    /**
     * @var $this
     */
    private static $_instance;

    /**
     * @return $this
     */
    final public static function getInstance()
    {
        return isset(static::$_instance)
            ? static::$_instance
            : static::$_instance = (new \ReflectionClass(get_called_class()))->newInstanceArgs(func_get_args());
    }

    final private function __construct() {
        call_user_func_array([$this,'init'], func_get_args());
    }

    protected function init() {}

    final private function __wakeup() {}

    final private function __clone() {}
}