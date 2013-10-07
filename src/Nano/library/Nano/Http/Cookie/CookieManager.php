<?php

namespace Nano\Http\Cookie;

use Nano\stdCls\ArrayCollection;

class CookieManager extends ArrayCollection
{

    protected $prefix = '!cm_';

    public function __construct()
    {

        $this->load();
    }

    public function load()
    {
        $this->clear();

        foreach($_COOKIE as $name => $value)
        {
            if (strpos($name ,$this->prefix)===0)
            {
                try
                {
                    /** @var Cookie $cookie */
                    $cookie = unserialize($value);
                    $this->offsetSet($cookie->getName(), $cookie);
                }
                catch (\Exception $e)
                {

                }
            }
        }

        return $this;
    }

    public function send()
    {
        /** @var Cookie $cookie */
        foreach($this as $cookie)
        {
            setcookie($cookie->getName(), $cookie, $cookie->getExpire(), $cookie->getPath(), $cookie->getDomain(), $cookie->getSecure(), $cookie->getHttpOnly());
        }

        return $this;
    }

    public function createCookie($name, $value = null, $expire = null, $path = null, $domain = null, $secure = null, $httponly = null)
    {
        $this->offsetSet($name, $cookie = new Cookie($name, $value, $expire, $path, $domain, $secure, $httponly));

        return $cookie;
    }

    public function offsetSet($index, $value)
    {
        if (!$value instanceof Cookie)
        {
            throw new \InvalidArgumentException();
        }
        parent::offsetSet($index, $value);

        return $this;
    }

    public function exchangeArray($input)
    {
        if (count($input))
        {
            throw new \BadMethodCallException();
        }
        parent::exchangeArray($input);

        return $this;
    }
}