<?php

namespace Nano\Http\Response;

use Nano\Behavior\EventInjection;
use Nano\Behavior\ServiceInjection;
use Nano\Http\Cookie\Cookie;
use Nano\Http\Cookie\CookieManager;
use Nano\Http\Headers;

abstract class AbstractResponse implements ResponseInterface
{
    use ServiceInjection;
    use EventInjection;

    /**
     * @var \Nano\Http\Headers
     */
    protected $headers;
    /**
     * @var \Nano\Http\Cookie\CookieManager
     */
    protected $cookies;
    /**
     * @var string
     */
    protected $content;

    public function __construct($content = '')
    {
        $this->headers = new Headers();
        $this->cookies = new CookieManager();
        $this->content = $content;
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    public function setHeader($name, $value)
    {
        return $this->headers->set($name, $value);
    }

    /**
     * @param string     $name
     * @param string     $name
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    public function getHeader($name, $default = null)
    {
        return $this->headers->get($name, $default);
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function getCookie($name)
    {
        return $this->cookies->get($name);
    }

    /**
     * @param string      $name
     * @param mixed|null  $value
     * @param int|null    $expire
     * @param string|null $path
     * @param string|null $domain
     * @param bool|null   $secure
     * @param bool|null   $httponly
     *
     * @return $this
     */
    public function setCookie($name, $value = null, $expire = null, $path = null, $domain = null, $secure = null, $httponly = null)
    {
        if ($name instanceof Cookie)
        {
            $this->cookies->set($name->getName(), $name);
        }
        else
        {
            $this->cookies->createCookie($name, $name, $value, $expire, $path, $domain, $secure, $httponly);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function send()
    {
        $this->headers->send();
        $this->cookies->send();
        echo $this->content;

        return $this;
    }
}