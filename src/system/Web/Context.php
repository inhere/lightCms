<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-23
 * Time: 9:58
 */

namespace Micro\Web;

use Inhere\Http\Response;
use Inhere\Http\ServerRequest;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Context - a simple htt request context
 * @package Micro\Web
 */
class Context
{
    /** @var ServerRequest */
    public $req;

    /** @var Response */
    public $res;

    public static function make(ServerRequestInterface $request, ResponseInterface $response)
    {
        return new static($request, $response);
    }

    /**
     * Context constructor.
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->req = $request;
        $this->res = $response;
    }

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