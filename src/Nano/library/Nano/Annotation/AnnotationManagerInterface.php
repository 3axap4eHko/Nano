<?php

namespace Nano\Annotation;

use Nano\Event\EventAwareInterface;
use Nano\Service\ServiceAwareInterface;

interface AnnotationManagerInterface  extends ServiceAwareInterface, EventAwareInterface
{
    public function registerParser($parser, $tagName = null);
}