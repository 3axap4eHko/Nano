<?php

namespace Nano\Http;

use Nano\Http\Cookie\CookieManager;
use Nano\Http\Session\SessionManager;
use Nano\stdCls\ArrayCollection;

class Request
{
    const METHOD_GET     = 'GET';
    const METHOD_POST    = 'POST';
    const METHOD_PUT     = 'PUT';
    const METHOD_DELETE  = 'DELETE';
    const METHOD_HEAD    = 'HEAD';
    const METHOD_OPTIONS = 'OPTIONS';

    const REQUESTED_WITH_XML_HTTP = 'XMLHttpRequest';

    /**
     * @var string
     */
    protected $uri;
    /**
     * @var string
     */
    protected $method;
    /**
     * @var bool
     */
    protected $isAjax;
    /**
     * @var \Nano\stdCls\ArrayCollection
     */
    protected $query;
    /**
     * @var \Nano\stdCls\ArrayCollection
     */
    protected $post;
    /**
     * @var \Nano\stdCls\ArrayCollection
     */
    protected $put;
    /**
     * @var \Nano\stdCls\ArrayCollection
     */
    protected $server;
    /**
     * @var Session\SessionManager
     */
    protected $session;
    /**
     * @var Cookie\CookieManager
     */
    protected $cookie;
    /**
     * @var \Nano\stdCls\ArrayCollection
     */
    protected $env;

    public function __construct()
    {
        $this->query = new ArrayCollection($_GET);
        $this->post  = new ArrayCollection($_POST);
        parse_str(file_get_contents('php://input'), $_PUT);
        $this->put     = new ArrayCollection((array)$_PUT);
        $this->server  = new ArrayCollection($_SERVER);
        $this->session = new SessionManager();
        $this->cookie  = new CookieManager();
        $this->env     = new ArrayCollection($_ENV);
        $this->uri     = $this->server->get('REQUEST_URI');
        $this->method  = $this->server->get('REQUEST_METHOD');
        $this->isAjax  = $this->server->get('HTTP_X_REQUESTED_WITH') === self::REQUESTED_WITH_XML_HTTP;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return bool
     */
    public function isMethod($method)
    {
        return $this->getMethod() === $method;
    }

    /**
     * @return boolean
     */
    public function isAjax()
    {
        return $this->isAjax;
    }

    /**
     * @return \Nano\stdCls\ArrayCollection
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return \Nano\stdCls\ArrayCollection
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @return \Nano\stdCls\ArrayCollection
     */
    public function getPut()
    {
        return $this->put;
    }

    /**
     * @return \Nano\Http\Session\SessionManager
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @return \Nano\Http\Cookie\CookieManager
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * @return \Nano\stdCls\ArrayCollection
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @return \Nano\stdCls\ArrayCollection
     */
    public function getEnv()
    {
        return $this->env;
    }
}