<?php

namespace Nano\Annotation;

class Annotation
{
    public $reflectionClass;

    public function __construct($className)
    {
        $this->reflectionClass = new \ReflectionClass($className);
    }
}