<?php

namespace GH\Controller;

use Nano\Http\Controller\Controller;

/**
  * Class IndexController
 * @package\test GH\Controller
 *  asd
 * @template({
 *      name: "a",
 *      type: "b"
 * })
 */
class IndexController extends Controller
{
    public function indexAction()
    {
        echo 222;
    }
}