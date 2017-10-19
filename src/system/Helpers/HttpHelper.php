<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-19
 * Time: 14:42
 */

namespace LightCms\Helpers;

use Inhere\Http\Cookies;
use Inhere\Http\Headers;
use Inhere\Http\Request;
use Inhere\Http\RequestBody;
use Inhere\Http\UploadedFile;
use Inhere\Http\Uri;
use Inhere\Library\Helpers\Http;
use Inhere\Library\Web\Environment;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class HttpHelper
 * @package LightCms\Helpers
 */
class HttpHelper extends Http
{
    const FAV_ICON = '/favicon.ico';

    /**
     * Special HTTP headers that do not have the "HTTP_" prefix
     * @var array
     */
    protected static $special = [
        'CONTENT_TYPE' => 1,
        'CONTENT_LENGTH' => 1,
        'PHP_AUTH_USER' => 1,
        'PHP_AUTH_PW' => 1,
        'PHP_AUTH_DIGEST' => 1,
        'AUTH_TYPE' => 1,
    ];

    /**
     * Create new HTTP request with data extracted from the application
     * Environment object
     * @param  Environment $env The Slim application Environment
     * @return ServerRequestInterface
     */
    public static function createRequest(Environment $env)
    {
        $method = $env['REQUEST_METHOD'];
        $uri = static::createRequestUri($env);
        $headers = static::createRequestHeaders($env);
        $cookies = Cookies::parseFromRawHeader($headers->get('Cookie', []));
        $serverParams = $env->all();
        $body = new RequestBody();
        $uploadedFiles = UploadedFile::createFromFILES();

        $request = new Request($method, $uri, $headers, $cookies, $serverParams, $body, $uploadedFiles);

        if ($method === 'POST' &&
            in_array($request->getMediaType(), ['application/x-www-form-urlencoded', 'multipart/form-data'], true)
        ) {
            // parsed body must be $_POST
            $request = $request->withParsedBody($_POST);
        }

        return $request;
    }

    /**
     * @param Environment $env
     * @return Headers
     */
    public static function createRequestHeaders(Environment $env)
    {
        $data = [];
        $env = self::determineAuthorization($env);
        foreach ($env as $key => $value) {
            $key = strtoupper($key);
            if (isset(static::$special[$key]) || strpos($key, 'HTTP_') === 0) {
                if ($key !== 'HTTP_CONTENT_LENGTH') {
                    $data[$key] = $value;
                }
            }
        }

        return new Headers($data);
    }

    /**
     * If HTTP_AUTHORIZATION does not exist tries to get it from
     * getallheaders() when available.
     * @param Environment $env The Slim application Environment
     * @return Environment
     */
    public static function determineAuthorization(Environment $env)
    {
        $authorization = $env->get('HTTP_AUTHORIZATION');

        if (null === $authorization && is_callable('getallheaders')) {
            $headers = getallheaders();
            $headers = array_change_key_case($headers, CASE_LOWER);
            if (isset($headers['authorization'])) {
                $env->set('HTTP_AUTHORIZATION', $headers['authorization']);
            }
        }

        return $env;
    }

    /**
     * @param Environment $env
     * @return Uri
     */
    public static function createRequestUri(Environment $env)
    {
        // Scheme
        $isSecure = $env->get('HTTPS');
        $scheme = (empty($isSecure) || $isSecure === 'off') ? 'http' : 'https';

        // Authority: Username and password
        $username = $env->get('PHP_AUTH_USER', '');
        $password = $env->get('PHP_AUTH_PW', '');

        // Authority: Host
        if ($env->has('HTTP_HOST')) {
            $host = $env->get('HTTP_HOST');
        } else {
            $host = $env->get('SERVER_NAME');
        }

        // Authority: Port
        $port = (int)$env->get('SERVER_PORT', 80);
        if (preg_match('/^(\[[a-fA-F0-9:.]+\])(:\d+)?\z/', $host, $matches)) {
            $host = $matches[1];

            if ($matches[2]) {
                $port = (int)substr($matches[2], 1);
            }
        } else {
            $pos = strpos($host, ':');
            if ($pos !== false) {
                $port = (int)substr($host, $pos + 1);
                $host = strstr($host, ':', true);
            }
        }

        // Path
        $requestScriptName = parse_url($env->get('SCRIPT_NAME'), PHP_URL_PATH);
        $requestScriptDir = dirname($requestScriptName);

        // parse_url() requires a full URL. As we don't extract the domain name or scheme,
        // we use a stand-in.
        $requestUri = parse_url('http://example.com' . $env->get('REQUEST_URI'), PHP_URL_PATH);

        $basePath = '';
        $virtualPath = $requestUri;
        if (stripos($requestUri, $requestScriptName) === 0) {
            $basePath = $requestScriptName;
        } elseif ($requestScriptDir !== '/' && stripos($requestUri, $requestScriptDir) === 0) {
            $basePath = $requestScriptDir;
        }

        if ($basePath) {
            $virtualPath = ltrim(substr($requestUri, strlen($basePath)), '/');
        }

        // Query string
        $queryString = $env->get('QUERY_STRING', '');
        if ($queryString === '') {
            $queryString = parse_url('http://example.com' . $env->get('REQUEST_URI'), PHP_URL_QUERY);
        }

        // Fragment
        $fragment = '';

        // Build Uri
        $uri = new Uri($scheme, $host, $port, $virtualPath, $queryString, $fragment, $username, $password);
        if ($basePath) {
            $uri = $uri->withBasePath($basePath);
        }

        return $uri;
    }
}