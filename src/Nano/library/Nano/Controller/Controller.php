<?php

namespace Nano\Controller;

use Nano\Behavior\EventInjection;
use Nano\Behavior\ServiceContainer;
use Nano\Behavior\ServiceInjection;
use Nano\Event\EventAwareInterface;
use Nano\Service\ServiceAwareInterface;

class Controller implements ServiceAwareInterface, EventAwareInterface
{
    use ServiceInjection;
    use EventInjection;
    use ServiceContainer;
}