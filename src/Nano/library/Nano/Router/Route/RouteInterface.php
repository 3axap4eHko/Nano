<?php

namespace Nano\Router\Route;

use Nano\stdCls\ArrayCollection;

interface RouteInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param $arguments
     *
     * @return bool
     */
    public function handle($arguments);

    /**
     * @return array
     */
    public function getDefaults();
    /**
     * @return ArrayCollection
     */
    public function getParameters();

    /**
     * @param $parameters
     *
     * @return $this
     */
    public function update($parameters);
}