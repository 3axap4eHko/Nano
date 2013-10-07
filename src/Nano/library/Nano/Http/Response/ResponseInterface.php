<?php

namespace Nano\Http\Response;

use Nano\Event\EventAwareInterface;
use Nano\Service\ServiceAwareInterface;

interface ResponseInterface extends ServiceAwareInterface, EventAwareInterface
{
    public function send();
}