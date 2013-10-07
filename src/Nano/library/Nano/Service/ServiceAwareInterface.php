<?php

namespace Nano\Service;

interface ServiceAwareInterface
{
    /**
     * @param \Nano\Service\ServiceManagerInterface $serviceManager
     *
     * @return $this
     */
    public function setServiceManager($serviceManager);
    /**
     * @return \Nano\Service\ServiceManagerInterface
     */
    public function getServiceManager();

}