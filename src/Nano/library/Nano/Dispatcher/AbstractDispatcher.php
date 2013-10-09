<?php

namespace Nano\Dispatcher;

use Nano\Behavior\EventInjection;
use Nano\Behavior\ServiceContainer;
use Nano\Behavior\ServiceInjection;
use Nano\Exception;
use Nano\Http\Route\AbstractRoute as HttpAbstractRoute;
use Nano\Service\ServiceManager as SM;
use Nano\Router\RouterInterface;
use Nano\Router\Route\RouteInterface;
use Nano\Dispatcher\Exception\NotFoundException;
use Nano\stdCls\ArrayCollection;
use Nano\Controller\Controller;

abstract class AbstractDispatcher implements DispatcherInterface
{
    use ServiceInjection;
    use EventInjection;
    use ServiceContainer;

    private $controllers = [];
    /**
     * @var RouteInterface
     */
    protected $currentRoute;
    /**
     * @var Controller
     */
    protected $currentController;

    public function forward($route, $parameters = [])
    {
        /** @var RouterInterface $router */
        $router             = $this->getServiceManager()->getShared(SM::SERVICE_ROUTER);
        $this->currentRoute = clone $router->getRoute($route);
        $this->currentRoute->update($parameters);

        return $this;
    }

    /**
     * @param RouteInterface $route
     *
     * @return array
     */
    protected function handleRoute(RouteInterface $route)
    {
        $totalParameters = (new ArrayCollection($route->getDefaults()))->merge($route->getParameters());
        if ($route instanceof HttpAbstractRoute)
        {
            $namespace = 'Controller';
        }
        else
        {
            $namespace = 'Command';
        }
        $handler = \Nano::parseHandler($totalParameters->get('_handler'), $namespace);

        return $handler;
    }

    /**
     * @param array $handler
     *
     * @return callable
     */
    protected function initialize(array $handler)
    {
        $controllerClass = array_shift($handler);
        if (!isset($this->controllers[$controllerClass]))
        {
            $this->controllers[$controllerClass] = new $controllerClass();
            $this->annotation->parseObject($this->controllers[$controllerClass]);

        }
        /** @var Controller $controller */
        $this->setCurrentController($controller = $this->controllers[$controllerClass]);
        $controller->setServiceManager($this->getServiceManager());
        $controller->setEventManager($this->getEventManager());

        $this->getEventManager()->attach('dispatcher:initialize', [$controller, 'initialize']);

        $actionMethod = array_shift($handler);
        if ($actionMethod)
        {
            $controller = [$controller, $actionMethod];
        }

        return $controller;
    }

    /**
     * @param callable       $controller
     * @param RouteInterface $route
     * @param mixed          $arguments
     *
     * @return mixed
     */
    protected function executeRoute($controller, RouteInterface $route, $arguments)
    {
        $parameters   = array_values($route->getParameters()->getArrayCopy());
        $parameters[] = $arguments;

        return call_user_func_array($controller, $parameters);
    }

    final public function dispatch($arguments)
    {
        $em = $this->getServiceManager()->getShared(SM::SERVICE_EVENT_MANAGER);
        $this->setEventManager($em);
        ob_start();
        /** @var RouterInterface $router */
        $router = $this->getServiceManager()->getShared(SM::SERVICE_ROUTER);
        try
        {
            if ($router->handle($arguments))
            {
                $this->setCurrentRoute($route = $router->getMatchedRoute());
            }
            else
            {
                throw new NotFoundException();
            }

            $this->getEventManager()->fire('dispatcher:beforeDispatchLoop', $this);
            do
            {
                $this->getEventManager()->fire('dispatcher:beforeDispatch', $this);

                $handler = $this->handleRoute($route);
                $controller = $this->initialize($handler);

                $this->getEventManager()->fire('dispatcher:initialize', $this);


                $this->getEventManager()->fire('dispatcher:beforeExecuteRoute', $this);

                $response = $this->executeRoute($controller, $route, $arguments);

                $this->getEventManager()->fire('dispatcher:afterExecuteRoute', $this);

                $this->getEventManager()->fire('dispatcher:afterDispatch', $this);
                $route = null;
            } while ($this->getCurrentRoute() === $route);


            $this->getEventManager()->fire('dispatcher:afterDispatchLoop', $this);


            $content = ob_get_clean();

        } catch (NotFoundException $e)
        {
            $this->getEventManager()->fire('dispatcher:beforeNotFoundAction', $this);
            if (!$router->hasRoute('notFound'))
            {
                throw new Exception('Route "notFound" does not exists');
            }
            $route = $router->getRoute('notFound');
            $handler = $this->handleRoute($route);
            $controller = $this->initialize($handler);
            $response = $this->executeRoute($controller, $route, $arguments);

        } catch (\Exception $e)
        {
            $this->getEventManager()->fire('dispatcher:beforeException', $this, [$e]);
        }
        die($content);
    }

    /**
     * @param \Nano\Controller\Controller $currentController
     *
     * @return $this
     */
    public function setCurrentController($currentController)
    {
        $this->currentController = $currentController;

        return $this;
    }

    /**
     * @return \Nano\Controller\Controller
     */
    public function getCurrentController()
    {
        return $this->currentController;
    }

    /**
     * @param \Nano\Router\Route\RouteInterface $currentRoute
     *
     * @return $this
     */
    public function setCurrentRoute($currentRoute)
    {
        $this->currentRoute = $currentRoute;

        return $this;
    }

    /**
     * @return \Nano\Router\Route\RouteInterface
     */
    public function getCurrentRoute()
    {
        return $this->currentRoute;
    }
}