<?php

namespace Nano\Http;

use Nano\stdCls\ArrayCollection;

class Headers
{
    /**
     * @var \Nano\stdCls\ArrayCollection
     */
    protected $headers;
    /**
     * @var string
     */
    protected $version;
    /**
     * @var int
     */
    protected $statusCode;
    /**
     * @var string
     */
    protected $statusText;

    public function __construct()
    {
        $this->headers    = new ArrayCollection();
        $this->version    = '1.1';
        $this->statusCode = 200;
        $this->statusText = 'OK';
    }

    /**
     * @param string $name
     * @param null   $default
     *
     * @return mixed|null
     */
    public function get($name, $default = null)
    {
        return $this->headers->get($name, $default);
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    public function set($name, $value)
    {
        return $this->headers->set($name, $value);
    }

    /**
     * @param string $version
     *
     * @return $this
     */
    public function setProtocolVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->version;
    }

    public function setStatus($code, $text)
    {
        $this->statusCode = $code;
        $this->statusText = $text;

        return $this;
    }

    public function send()
    {
        if (headers_sent())
        {
            return $this;
        }

        // status
        header("HTTP/{$this->version} {$this->statusCode} {$this->statusText}");

        // headers
        foreach ($this->headers as $name => $values)
        {
            foreach ($values as $value)
            {
                header($name . ': ' . $value, false);
            }
        }

        // cookies
        foreach ($this->headers->getCookies() as $cookie)
        {
            setcookie($cookie->getName(), $cookie->getValue(), $cookie->getExpiresTime(), $cookie->getPath(), $cookie->getDomain(), $cookie->isSecure(), $cookie->isHttpOnly());
        }

        return $this;
    }
}