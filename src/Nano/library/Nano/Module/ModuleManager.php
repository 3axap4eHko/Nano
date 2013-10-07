<?php

namespace Nano\Module;

use Nano\Behavior\EventInjection;
use Nano\Behavior\ServiceInjection;
use Nano\Event\EventAwareInterface;
use Nano\Loader;
use Nano\Config;
use Nano\Service\ServiceAwareInterface;
use Nano\Service\ServiceManager as SM;
use Nano\stdCls\ArrayCollection;

class ModuleManager implements ServiceAwareInterface, EventAwareInterface
{
    use ServiceInjection;
    use EventInjection;

    protected $modules;

    public function __construct($serviceManager = null)
    {
        $this->modules = new ArrayCollection();
        $this->setServiceManager($serviceManager);
    }

    public function register($name, $path)
    {
        $moduleDir = sprintf('%s/%s', $path, str_replace('\\', DIRECTORY_SEPARATOR, $name));
        $this->modules->offsetSet($name, $moduleDir);

        /** @var Loader $loader */
        $loader = $this->getServiceManager()->get(SM::SERVICE_LOADER);
        $loader->registerNamespace($name, $moduleDir);
        /** @var Config $config */
        $config = $this->getServiceManager()->getShared(SM::SERVICE_CONFIG);


        $config->merge(Config::fromFile($moduleDir . '/Resources/config', 'global.config'));
        $config->merge(Config::fromFile($moduleDir . '/Resources/config', 'local.config'));

        return $this;
    }

    public function getModuleDir($name)
    {
        return $this->modules->get($name);
    }
}