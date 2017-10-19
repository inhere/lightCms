<?php
/**
 * Slim Framework (https://slimframework.com)
 * @link      https://github.com/slimphp/Slim
 * @copyright Copyright (c) 2011-2017 Josh Lockhart
 * @license   https://github.com/slimphp/Slim/blob/3.x/LICENSE.md (MIT License)
 */

namespace LightCms\Web\Handlers;

use Inhere\Http\Body;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use UnexpectedValueException;

/**
 * Default application error handler
 * It outputs the error message and diagnostic information in either JSON, XML,
 * or HTML based on the Accept header.
 */
class ErrorRenderer extends AbstractError
{
    /**
     * Invoke error handler
     * @param ServerRequestInterface $request The most recent Request object
     * @param ResponseInterface $response The most recent Response object
     * @param \Exception|\Throwable $error The caught Exception object
     * @return ResponseInterface
     * @throws UnexpectedValueException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $error)
    {
        $contentType = $this->determineContentType($request);
        switch ($contentType) {
            case 'application/json':
                $output = $this->renderJsonErrorMessage($error);
                break;

            case 'text/xml':
            case 'application/xml':
                $output = $this->renderXmlErrorMessage($error);
                break;

            case 'text/html':
                $output = $this->renderHtmlErrorMessage($error);
                break;

            default:
                throw new UnexpectedValueException('Cannot render unknown content type ' . $contentType);
        }

        $this->writeToErrorLog($error);

        $body = new Body(fopen('php://temp', 'rb+'));
        $body->write($output);

        return $response
            ->withStatus(500)
            ->withHeader('Content-type', $contentType)
            ->withBody($body);
    }

    /**
     * Render HTML error page
     * @param  \Exception|\Throwable $error
     * @return string
     */
    protected function renderHtmlErrorMessage($error)
    {
        $title = 'Application Runtime Error(Exception)';

        if ($this->displayErrorDetails) {
            $html = '<p>The application could not run because of the following error:</p>';
            $html .= '<h2>Details</h2>';
            $html .= $this->renderHtmlException($error);

            while ($error = $error->getPrevious()) {
                $html .= '<h2>Previous exception</h2>';
                $html .= $this->renderHtmlExceptionOrError($error);
            }
        } else {
            $html = '<p>A website error has occurred. Sorry for the temporary inconvenience.</p>';
        }

        $output = sprintf(<<<EOF
<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<title>%s</title><style>body{margin:0;padding:30px;font:14px/1.5 Helvetica,Arial,Verdana,sans-serif;}
h1{margin:0;font-size:48px;font-weight:normal;line-height:48px;}
strong{display:inline-block;width:65px;}
pre{background-color: #f6f8fa;border-radius: 3px;padding: 16px;}
</style></head><body><h1>%s</h1>%s</body></html>
EOF
            ,
            $title,
            $title,
            $html
        );

        return $output;
    }

    /**
     * Render exception as HTML.
     * Provided for backwards compatibility; use renderHtmlExceptionOrError().
     * @param \Exception|\Throwable $error
     * @return string
     */
    protected function renderHtmlException($error)
    {
        return $this->renderHtmlExceptionOrError($error);
    }

    /**
     * Render exception or error as HTML.
     * @param \Exception|\Error|\Throwable $error
     * @return string
     */
    protected function renderHtmlExceptionOrError($error)
    {
        $html = sprintf('<div><strong>Type:</strong> %s</div>', get_class($error));

        if ($code = $error->getCode()) {
            $html .= sprintf('<div><strong>Code:</strong> %s</div>', $code);
        }

        if ($message = $error->getMessage()) {
            $html .= sprintf('<div><strong>Message:</strong> %s</div>', htmlentities($message));
        }

        if ($file = $error->getFile()) {
            $html .= sprintf('<div><strong>File:</strong> %s</div>', $file);
        }

        if ($line = $error->getLine()) {
            $html .= sprintf('<div><strong>Line:</strong> %s</div>', $line);
        }

        if ($trace = $error->getTraceAsString()) {
            $html .= '<h2>Trace</h2>';
            $html .= sprintf('<pre>%s</pre>', htmlentities($trace));
        }

        return $html;
    }

    /**
     * Render JSON error
     * @param \Exception|\Throwable $error
     * @return string
     */
    protected function renderJsonErrorMessage($error)
    {
        $json = [
            'message' => 'Application Runtime Error(Exception)',
        ];

        if ($this->displayErrorDetails) {
            $json['exception'] = [];

            do {
                $json['exception'][] = [
                    'type' => get_class($error),
                    'code' => $error->getCode(),
                    'message' => $error->getMessage(),
                    'file' => $error->getFile(),
                    'line' => $error->getLine(),
                    'trace' => explode("\n", $error->getTraceAsString()),
                ];
            } while ($error = $error->getPrevious());
        }

        return json_encode($json, JSON_PRETTY_PRINT);
    }

    /**
     * Render XML error
     * @param \Exception|\Throwable $error
     * @return string
     */
    protected function renderXmlErrorMessage($error)
    {
        $xml = "<error>\n  <message>Slim Application Error</message>\n";
        if ($this->displayErrorDetails) {
            do {
                $xml .= "  <error>\n";
                $xml .= '    <type>' . get_class($error) . "</type>\n";
                $xml .= '    <code>' . $error->getCode() . "</code>\n";
                $xml .= '    <message>' . $this->createCdataSection($error->getMessage()) . "</message>\n";
                $xml .= '    <file>' . $error->getFile() . "</file>\n";
                $xml .= '    <line>' . $error->getLine() . "</line>\n";
                $xml .= '    <trace>' . $this->createCdataSection($error->getTraceAsString()) . "</trace>\n";
                $xml .= "  </error>\n";
            } while ($error = $error->getPrevious());
        }
        $xml .= '</error>';

        return $xml;
    }

    /**
     * Returns a CDATA section with the given content.
     * @param  string $content
     * @return string
     */
    private function createCdataSection($content)
    {
        return sprintf('<![CDATA[%s]]>', str_replace(']]>', ']]]]><![CDATA[>', $content));
    }
}
