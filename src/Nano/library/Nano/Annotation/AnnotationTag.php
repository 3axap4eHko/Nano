<?php

namespace Nano\Annotation;

class AnnotationTag
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $arguments;

    /**
     * @param string $name
     * @param string $arguments
     */
    public function __construct($name, $arguments = '')
    {
        $this->setName($name);
        $this->setArguments($arguments);
    }

    /**
     * @param string $name
     * @param string $arguments
     *
     * @return static
     */
    public static function create($name, $arguments = '')
    {
        return new static($name, $arguments);
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
     * @param string $arguments
     *
     * @return $this
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @return string
     */
    public function getArguments()
    {
        return $this->arguments;
    }
}