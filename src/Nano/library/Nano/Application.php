<?php

namespace Nano;

use Nano\Behavior\ApplicationInitializer;
use Nano\Behavior\ServiceInjection;
use Nano\Behavior\EventInjection;
use Nano\Console\Arguments;
use Nano\Event\EventAwareInterface;
use Nano\Http\Request;
use Nano\Service\ServiceAwareInterface;
use Nano\Service\ServiceManager as SM;
use Nano\Dispatcher\DispatcherInterface;

class Application implements ServiceAwareInterface, EventAwareInterface
{
    use ServiceInjection;
    use EventInjection;
    use ApplicationInitializer;

    public function __construct($serviceManager = null)
    {
        $this->setServiceManager($serviceManager);
    }

    public function run()
    {
        if (strpos(PHP_SAPI, 'cli') ===false)
        {
            $arguments = new Request();
        }
        else
        {
            $arguments = new Arguments();
        }
        /** @var DispatcherInterface $dispatcher */
        $dispatcher = $this->getServiceManager()->getShared(SM::SERVICE_DISPATCHER);

        $dispatcher->dispatch($arguments);

        return $this;
    }
}