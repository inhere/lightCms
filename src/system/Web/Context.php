<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-23
 * Time: 9:58
 */

namespace LightCms\Web;

use Inhere\Http\Response;
use Inhere\Http\ServerRequest;

/**
 * Class Context - a simple htt request context
 * @package LightCms\Web
 */
class Context
{
    /** @var ServerRequest */
    public $req;

    /** @var Response */
    public $res;

    /**
     * @return ServerRequest
     */
    public function getRequest(): ServerRequest
    {
        return $this->req;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->res;
    }
}