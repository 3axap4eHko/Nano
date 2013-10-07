<?php

namespace Nano\Router;

use Nano\Router\Route\RouteInterface;
use Nano\stdCls\ArrayCollection;

interface RouterInterface
{
    /**
     * @param RouteInterface $route
     *
     * @return $this
     */
    public function addRoute(RouteInterface $route);

    /**
     * @param string $name
     *
     * @return RouteInterface|null
     */
    public function getRoute($name);

    /**
     * @param $arguments
     *
     * @return bool
     */
    public function handle($arguments);

    /**
     * @return RouteInterface|null
     */
    public function getMatchedRoute();

    /**
     * @param string $type
     * @param string $className
     *
     * @return $this
     */
    public function registerType($type, $className);

    /**
     * @param string          $type
     * @param string          $name
     * @param ArrayCollection $params
     *
     * @return RouteInterface
     */
    public function create($type, $name,  ArrayCollection $params);


}