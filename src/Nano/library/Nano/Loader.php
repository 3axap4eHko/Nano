<?php

namespace Nano;

class Loader
{
    protected $namespaces;

    public function __construct(array $namespaces = [])
    {
        $this->registerNamespaces($namespaces);
        spl_autoload_register($this);
    }

    public function registerNamespace($namespace, $path)
    {
        $this->namespaces[$namespace] = realpath($path);

        return $this;
    }

    public function registerNamespaces(array $namespaces)
    {
        foreach($namespaces as $namespace => $path)
        {
            $this->registerNamespace($namespace, $path);
        }

        return $this;
    }

    public function __invoke($className)
    {
        $this->load($className);
        return $this;
    }

    protected function hasNamespace($className, $namespace)
    {
        return strpos($className, $namespace) === 0;
    }

    protected function class2file($path, $className)
    {

        $parts = explode('\\',$className);
        $parts[0] = $path;
        return implode(DIRECTORY_SEPARATOR, $parts) . '.php';
    }

    public function load($className)
    {
        foreach($this->namespaces as $namespace => $path)
        {
            if ($this->hasNamespace($className, $namespace) && file_exists($filename = $this->class2file($path, $className)))
            {
                require_once $filename;
            }
        }
    }
}