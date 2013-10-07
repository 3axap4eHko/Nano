<?php

namespace Nano\Behavior;

use Nano\Config;
use Nano\Http\Route\Route;
use Nano\Loader;
use Nano\Module\ModuleManager;
use Nano\Service\ServiceAwareInterface;
use Nano\Service\ServiceFactory;
use Nano\Service\ServiceManager as SM;
use Nano\Event\EventAwareInterface;
use Nano\Event\EventManagerInterface;
use Nano\Dispatcher\DispatcherInterface;
use Nano\Console\Dispatcher as ConsoleDispatcher;
use Nano\Http\Dispatcher as HttpDispatcher;
use Nano\Router\RouterInterface;

trait ApplicationInitializer
{
    use ServiceContainer;

    private $initOrder = [
        SM::SERVICE_CONFIG,
        SM::SERVICE_MODULES,
        SM::SERVICE_LOADER,
        SM::SERVICE_SERVICES,
        SM::SERVICE_PLUGINS,
        SM::SERVICE_DISPATCHER,
        SM::SERVICE_ROUTER
    ];

    public function init($applicationConfig)
    {
        if (!$this instanceof ServiceAwareInterface)
        {
            throw new \Exception();
        }
        $applicationConfig = realpath($applicationConfig);
        $config = Config::fromFile($applicationConfig);

        if (!$this->getServiceManager())
        {
            $serviceManagerClass = $config->get('serviceManager', 'Nano\Service\DefaultServiceManager');
            $this->setServiceManager(new $serviceManagerClass());
        }
        $sm = $this->getServiceManager();
        $sm->setShared(SM::SERVICE_APPLICATION, $this);
        $sm->setShared(SM::SERVICE_CONFIG, $config);

        foreach($this->initOrder as $section)
        {
            $method = '_init' . ucfirst($section);
            if (method_exists($this, $method))
            {
                $options = $config->has($section) ? $config->get($section) : new Config();
                call_user_func_array([$this, $method], [$options]);
            }
        }

        return $this;
    }

    private function _initConfig(Config $config)
    {
        /** @var ServiceAwareInterface $this */
        $sm = $this->getServiceManager();
        /** @var Config $globalConfig */
        $globalConfig = $sm->getShared(SM::SERVICE_CONFIG);
        $globalConfig->merge(Config::fromFile($config->directory, 'global.config'));
        $globalConfig->merge(Config::fromFile($config->directory, 'local.config'));

        return $this;
    }

    private function _initModules(Config $config)
    {
        /** @var ServiceAwareInterface $this */
        $sm = $this->getServiceManager();
        $moduleManager = new ModuleManager();
        $moduleManager->setServiceManager($sm);
        $sm->setShared(SM::SERVICE_MODULES, $moduleManager);
        foreach($config as $modulesDir => $moduleList)
        {
            foreach($moduleList as $name)
            {
                $moduleManager->register($name, $modulesDir);
            }
        }

        return $this;
    }

    private function _initLoader(Config $config)
    {
        /** @var ServiceAwareInterface $this */
        $sm = $this->getServiceManager();
        /** @var Loader $loader */
        $loader = $sm->getShared(SM::SERVICE_LOADER);
        $loader->registerNamespaces($config->getArrayCopy());

        return $this;
    }

    private function _initServices(Config $config)
    {
        /** @var ServiceAwareInterface $this */
        $sm = $this->getServiceManager();
        /** @var Config $globalConfig */
        $globalConfig = $sm->get(SM::SERVICE_CONFIG);
        foreach ($config as $serviceName => $serviceClass)
        {
            $options =  $globalConfig->get($serviceName);
            if ($options instanceof Config)
            {
                $options = $options->getArrayCopy();
            }
            $sm->set($serviceName, ServiceFactory::create($serviceClass, $options));
        }
        /** @var EventAwareInterface $this */
        $this->setEventManager($sm->get(SM::SERVICE_EVENT_MANAGER));

        return $this;
    }

    private function _initPlugins(Config $config)
    {
        /** @var ServiceAwareInterface $this */
        $sm = $this->getServiceManager();
        /** @var EventManagerInterface $eventsManager */
        $eventsManager = $sm->get(SM::SERVICE_EVENT_MANAGER);
        $priority = 0;
        foreach($config as $pluginClass => $domains)
        {
            $plugin = new $pluginClass($sm);
            foreach((array)$domains as $domain)
            {
                $eventsManager->attach($domain, $plugin, $priority++);
            }
        }

        return $this;
    }

    private function _initDispatcher(Config $config)
    {
        /** @var ServiceAwareInterface $this */
        $sm = $this->getServiceManager();
        /** @var EventManagerInterface $eventsManager */
        $eventsManager = $sm->get(SM::SERVICE_EVENT_MANAGER);
        /** @var DispatcherInterface $dispatcher */
        $dispatcher = $sm->getShared(SM::SERVICE_DISPATCHER);
        $dispatcher->setServiceManager($sm);
        $dispatcher->setEventManager($eventsManager);

        return $this;
    }

    private function _initRouter(Config $config)
    {

        /** @var ServiceAwareInterface $this */
        $sm = $this->getServiceManager();
        /** @var RouterInterface $router */
        $router = $sm->getShared(SM::SERVICE_ROUTER);
        $defaultType = $config->get('defaultType','http');
        foreach((array)$config->get('types') as $type => $className)
        {
            $router->registerType($type, $className);
        }

        /** @var Config $params */
        foreach((array)$config->get('routes') as $name => $params)
        {
            $type = $params->get('type', $defaultType);
            $route = $router->create($type, $name, $params);
            $route->setName($name);
            $router->addRoute($route);
        }

        return $this;
    }


}