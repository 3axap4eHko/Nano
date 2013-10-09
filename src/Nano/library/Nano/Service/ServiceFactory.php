<?php

namespace Nano\Service;

use Nano\Behavior\ServiceInjection;

class ServiceFactory implements ServiceFactoryInterface
{
    use ServiceInjection;

    /**
     * @var string
     */
    protected $className;
    /**
     * @var array
     */
    protected $arguments;

    final protected function __construct($className, array $arguments = [])
    {
        $this->className = $className;
        $this->arguments = $arguments;
    }

    /**
     * @param string $className
     * @param array  $arguments
     *
     * @return static
     */
    public static function create($className, array $arguments = [])
    {
        return new static($className, $arguments);
    }

    /**
     * @param array $arguments
     *
     * @return mixed
     */
    public function instantiate(array $arguments = [])
    {
        $reflection = new \ReflectionClass($this->className);
        if (!count($arguments))
        {
            $arguments = $this->arguments;
        }
        $instance = $reflection->newInstanceArgs($arguments);
        if ($this->getServiceManager() && $instance instanceof ServiceAwareInterface)
        {
            $instance->setServiceManager($this->getServiceManager());
        }
        return $instance;
    }

    /**
     * @param string $className
     * @param array  $arguments
     *
     * @return object
     */
    public static function instantiateClass($className, array $arguments = [])
    {
        $reflection = new \ReflectionClass($className);

        return $reflection->newInstanceArgs($arguments);
    }

    /**
     * @param string $className
     *
     * @return array|\ReflectionParameter[]
     */
    public static function getConstructorParameters($className)
    {
        $reflection = new \ReflectionClass($className);
        $constructor = $reflection->getConstructor();
        return $constructor ? $constructor->getParameters() : [];
    }

}