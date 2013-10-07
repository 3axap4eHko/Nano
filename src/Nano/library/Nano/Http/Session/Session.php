<?php

namespace Nano\Http\Session;

use Nano\stdCls\ArrayCollection;

class Session extends ArrayCollection
{
    protected $sessionId;

    public function __construct($namespace)
    {
        $this->sessionId = session_id();
        if (empty($this->sessionId))
        {
            session_start();
            $this->sessionId = session_id();
        }
        if (isset($_SESSION[$namespace]) && ($session = $_SESSION[$namespace]) instanceof Session)
        {
            $this->exchangeArray($session->getArrayCopy());
        }
        $_SESSION[$namespace] = $this;
    }
}