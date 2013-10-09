<?php

namespace Nano\Router;

use Nano\Behavior\EventInjection;
use Nano\Behavior\ServiceInjection;
use Nano\Config;
use Nano\Event\EventAwareInterface;
use Nano\Http\Request;
use Nano\Router\Route\RouteInterface;
use Nano\Service\ServiceAwareInterface;
use Nano\Service\ServiceFactory;
use Nano\stdCls\ArrayCollection;

class Router implements ServiceAwareInterface, EventAwareInterface
{
    use ServiceInjection;
    use EventInjection;

    /**
     * @var \Nano\stdCls\ArrayCollection
     */
    protected $routes;
    /**
     * @var RouteInterface|null
     */
    protected $matchedRoute;
    /**
     * @var ArrayCollection
     */
    protected $types = [
        'regexp' => 'Nano\Router\Route\RegExpRoute',
        'http'   => 'Nano\Http\Route\Route',
    ];

    public function __construct()
    {
        $this->routes = new ArrayCollection();
        $this->types = new ArrayCollection($this->types);
    }

    /**
     * @param RouteInterface $route
     *
     * @return $this
     */
    public function addRoute(RouteInterface $route)
    {
        $this->routes->set($route->getName(), $route);

        return $this;
    }

    /**
     * @param $name
     *
     * @return RouteInterface|null
     */
    public function getRoute($name)
    {
        return $this->routes->get($name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasRoute($name)
    {
        return $this->routes->has($name);
    }

    /**
     * @param Request $arguments
     *
     * @return bool
     */
    public function handle($arguments)
    {
        /** @var RouteInterface $route */
        foreach ($this->routes as $route)
        {
            if ($route->handle($arguments))
            {
                $this->matchedRoute = $route;

                return true;
            }
        }

        return false;
    }

    /**
     * @return RouteInterface|null
     */
    public function getMatchedRoute()
    {
        return $this->matchedRoute;
    }

    /**
     * @param string $type
     * @param string $className
     *
     * @return $this
     */
    public function registerType($type, $className)
    {
        $this->types->set($type, $className);

        return $this;
    }

    /**
     * @param string          $type
     * @param string          $name
     * @param ArrayCollection $params
     *
     * @return mixed
     */
    public function create($type, $name, $params)
    {
        $className = $this->types->get($type);
        $constructorParameters = ServiceFactory::getConstructorParameters($className);
        $params->set('name', $name);
        $arguments = new Config();
        /** @var \ReflectionParameter $parameter */
        foreach($constructorParameters as $parameter)
        {
            $value = $params->get($parameter->getName(), $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null);
            $arguments->append($value);
        }

        $route = ServiceFactory::instantiateClass($className, $arguments->getArrayCopy());


        return $route;
    }


}