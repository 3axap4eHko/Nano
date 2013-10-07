<?php

namespace Nano\Http\Session;

use Nano\stdCls\ArrayCollection;

class SessionManager
{
    public function createSession($namespace)
    {
        return new Session($namespace);
    }

    public function close()
    {
        return true;
    }


}