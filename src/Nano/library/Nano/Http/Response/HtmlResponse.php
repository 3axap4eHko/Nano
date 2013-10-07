<?php

namespace Nano\Http\Response;

class HtmlResponse extends AbstractResponse
{
    public function __construct($content = '')
    {
        parent::__construct($content);
        $this->headers->set('Content-Type','text/html');
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }


}