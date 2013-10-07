<?php

namespace Nano\Http\Response;

class JsonResponse extends AbstractResponse
{

    public function __construct($data = [])
    {
        parent::__construct();
        $this->headers->set('Content-Type', 'application/json');
        $this->setData($data);
    }

    /**
     * @param mixed $data
     * @param int   $options
     *
     * @return $this
     */
    public function setData($data, $options = 0)
    {
        $this->content = json_encode($data, $options);

        return $this;
    }
}