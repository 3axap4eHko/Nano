<?php

namespace Nano\Console\Route;

use Nano\Console\Arguments;
use Nano\Router\Route\AbstractRoute as NanoRoute;

abstract class AbstractRoute extends NanoRoute
{
    public function handle($arguments)
    {
        if ($arguments instanceof Arguments)
        {
            return parent::handle($arguments->getCommandline());
        }

        return false;
    }

}