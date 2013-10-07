<?php

namespace Nano\stdCls;

class ArrayCollection extends \ArrayObject
{
    const TYPE_PHP = '.php';

    /**
     * @param array $input
     */
    public function __construct($input = [])
    {
        parent::__construct(self::recursiveFetch($input));
    }

    /**
     * @param $config
     * @return mixed
     */
    private static function recursiveFetch($config)
    {
        foreach($config as &$data)
        {
            if (is_array($data))
            {
                $data = new static(self::recursiveFetch($data));
            }
        }

        return $config;
    }

    /**
     * @param $config
     *
     * @return mixed
     */
    private static function recursiveUnFetch($config)
    {
        foreach($config as &$data)
        {
            if ($data instanceof static)
            {
                $data = self::recursiveUnFetch((array)$data);
            }
        }

        return $config;
    }

    /**
     * @param mixed $input
     * @return $this
     */
    public function exchangeArray($input)
    {
        parent::exchangeArray(self::recursiveFetch($input));

        return $this;
    }

    /**
     * @return array
     */
    public function getArrayCopy()
    {
        return self::recursiveUnFetch(parent::getArrayCopy());
    }
    /**
     * Alias for offsetExists
     *
     * @param mixed $index
     * @return bool
     */
    public function has($index)
    {
        return $this->offsetExists($index);
    }

    /**
     * Extends method offsetGet
     *
     * @param mixed $index
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get($index, $default = null)
    {
        return $this->offsetExists($index) ? $this->offsetGet($index) : $default;
    }

    /**
     * Alias for offsetSet
     *
     * @param mixed $index
     * @param mixed $value
     * @return $this
     */
    public function set($index, $value)
    {
        $this->offsetSet($index, $value);

        return $this;
    }

    /**
     * @param mixed $index
     * @param mixed $value
     * @return $this
     */
    public function offsetSet($index, $value)
    {
        if (is_array($value))
        {
            $value = new static($value);
        }
        parent::offsetSet($index, $value);

        return $this;
    }


    /**
     * @param callable $callback
     * @return array
     * @throws \InvalidArgumentException
     */
    public function map($callback)
    {
        if (!is_callable($callback))
        {
            throw new \InvalidArgumentException('Argument 1 passed to ' . __METHOD__ . ' must be a callable');
        }
        $mapped = new ArrayCollection();
        foreach($this as $key => $value)
        {
            $mapped->set($key, $callback($value, $key));
        }

        return $mapped;
    }
    /**
     * @param callable $callback
     * @return array
     * @throws \InvalidArgumentException
     */
    public function filter($callback)
    {
        if (!is_callable($callback))
        {
            throw new \InvalidArgumentException('Argument 1 passed to ' . __METHOD__ . ' must be a callable');
        }
        $filtered = new ArrayCollection();
        foreach($this as $key => $value)
        {
            if ($callback($value, $key))
            {
                $filtered->set($key, $value);
            }
        }

        return $filtered;
    }

    /**
     * @param int  $offset
     * @param int  $length
     * @param bool $preserveKeys
     *
     * @return ArrayCollection
     */
    public function slice($offset, $length = null, $preserveKeys = null)
    {
        return new ArrayCollection(array_slice($this->getArrayCopy(), $offset, $length, $preserveKeys));

    }

    /**
     * @return $this
     */
    public function clear()
    {
        $this->exchangeArray([]);

        return $this;
    }

    /**
     * @param ArrayCollection $array1
     * @param ArrayCollection $array2
     *
     * @return ArrayCollection
     */
    final private static function recursiveMerge(ArrayCollection $array1, ArrayCollection $array2)
    {
        foreach($array2 as $key => $value)
        {
            if ($value instanceof ArrayCollection && $array1->get($key) instanceof ArrayCollection)
            {
                $value = self::recursiveMerge($array1->get($key), $value);
            }
            $array1->set($key, $value);
        }

        return $array1;
    }

    /**
     * @param ArrayCollection $config
     * @return $this
     */
    public function merge(ArrayCollection $config)
    {
        self::recursiveMerge($this, $config);

        return $this;
    }

    /**
     * @param $index
     * @return mixed|null
     */
    public function __get($index)
    {
        return $this->get($index);
    }

}