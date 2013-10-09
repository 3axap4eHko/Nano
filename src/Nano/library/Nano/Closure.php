<?php

namespace Nano;

class Closure
{
    protected $callback;
    protected $arguments;

    public function __construct($callback, array $arguments = [])
    {
        $this->callback = $callback;
        $this->arguments = $arguments;
    }

    public static function create($callback, array $arguments = [])
    {
        return new static($callback, $arguments);
    }

    public function __invoke()
    {
        return call_user_func_array($this->callback, func_num_args() ? func_get_args() : $this->arguments);
    }
}