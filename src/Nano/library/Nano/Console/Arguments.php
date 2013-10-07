<?php

namespace Nano\Console;

use Nano\stdCls\ArrayCollection;

class Arguments
{
    /**
     * @var string
     */
    protected $commandline;
    /**
     * @var \Nano\stdCls\ArrayCollection
     */
    protected $arguments;
    /**
     * @var \Nano\stdCls\ArrayCollection
     */
    protected $server;
    /**
     * @var \Nano\stdCls\ArrayCollection
     */
    protected $env;

    public function __construct()
    {
        $this->server = new ArrayCollection($_SERVER);
        $this->arguments = new ArrayCollection(array_slice($this->server->get('argv',[]),1));
        $this->env = new ArrayCollection($_ENV);
        $this->commandline = implode(' ', $this->arguments->getArrayCopy());
    }

    /**
     * @return string
     */
    public function getCommandline()
    {
        return $this->commandline;
    }

    public function get($name, $default = null)
    {
        return $this->arguments->get($name, $default);
    }

    public function all()
    {
        return $this->arguments->getArrayCopy();
    }

    public function server()
    {
        return $this->server;
    }

    public function env()
    {
        return $this->env;
    }
}