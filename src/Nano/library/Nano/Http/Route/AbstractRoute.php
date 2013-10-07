<?php

namespace Nano\Http\Route;

use Nano\Http\Request;
use Nano\Router\Route\RegExpRoute;
use Nano\stdCls\ArrayCollection;

abstract class AbstractRoute extends RegExpRoute
{
    /**
     * @var array
     */
    protected $methods = [];

    /**
     * @param array $methods
     * @return $this
     */
    public function setMethods($methods)
    {
        $this->methods = (array)$methods;

        return $this;
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param Request $arguments
     * @return bool
     */
    public function handle($arguments)
    {
        if ($arguments instanceof Request && !count($this->getMethods()) || in_array($arguments->getMethod(), $this->getMethods(), true))
        {
            return parent::handle($arguments->getUri());
        }

        return false;
    }

}