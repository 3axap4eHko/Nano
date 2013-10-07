<?php
namespace Nano\Http\Cookie;

class Cookie
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var mixed
     */
    protected $value;
    /**
     * @var \DateTime
     */
    protected $expire;
    /**
     * @var string
     */
    protected $path;
    /**
     * @var string
     */
    protected $domain;
    /**
     * @var bool
     */
    protected $secure;
    /**
     * @var bool
     */
    protected $httpOnly;

    public function __construct($name, $value = null, $expire = null, $path = null, $domain = null, $secure = null, $httponly = null)
    {
        $this->setName($name);
        $this->setValue($value);
        if ($expire !== null)
        {
            $this->setExpire($expire);
        }
        $this->setPath($path);
        $this->setDomain($domain);
        $this->setSecure($secure);
        $this->setHttpOnly($httponly);
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param \DateTime $expire
     *
     * @return $this
     */
    public function setExpire($expire)
    {
        $this->expire = new \DateTime($expire);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getExpire()
    {
        return $this->expire !== null ? $this->expire->getTimestamp() : null;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $domain
     *
     * @return $this
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param boolean $secure
     *
     * @return $this
     */
    public function setSecure($secure)
    {
        $this->secure = $secure;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getSecure()
    {
        return $this->secure;
    }

    /**
     * @param boolean $httpOnly
     *
     * @return $this
     */
    public function setHttpOnly($httpOnly)
    {
        $this->httpOnly = $httpOnly;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getHttpOnly()
    {
        return $this->httpOnly;
    }

    public function __toString()
    {
        return serialize($this);
    }
}