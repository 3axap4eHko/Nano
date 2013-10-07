<?php

namespace Nano\Router\Route;

use Nano\stdCls\ArrayCollection;

abstract class AbstractRoute implements RouteInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var array
     */
    protected $defaults;

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
     * @param ArrayCollection $parameters
     *
     * @return $this
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return ArrayCollection|null
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $defaults
     *
     * @return $this
     */
    public function setDefaults($defaults)
    {
        $this->defaults = $defaults;

        return $this;
    }

    /**
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * @param ArrayCollection $parameters
     *
     * @return $this
     */
    public function update($parameters)
    {
        $this->setParameters($parameters);

        return $this;
    }
}