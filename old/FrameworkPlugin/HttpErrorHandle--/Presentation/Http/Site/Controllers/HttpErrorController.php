<?php

namespace Untek\FrameworkPlugin\HttpErrorHandle\Presentation\Http\Site\Controllers;

use axy\backtrace\Trace;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Untek\Component\Env\Helpers\EnvHelper;
use Untek\Core\Contract\Common\Exceptions\InvalidConfigException;
use Untek\Persistence\Contract\Exceptions\NotFoundException;

class HttpErrorController
{
    public function __construct(
        private LoggerInterface $logger,
        private ?string $loginUrl = null,
    )
    {
        $this->logger = $logger;
    }

    public function handleError(Request $request, \Throwable $exception): Response
    {
        $data = [
            'attributes' => $request->attributes->all(),
            'request' => $request->request->all(),
            'query' => $request->query->all(),
            'server' => $request->server->all(),
            'files' => $request->files->all(),
            'cookies' => $request->cookies->all(),
            'headers' => $request->headers->all(),
            'requestUri' => $request->getRequestUri(),
            'method' => $request->getMethod(),
        ];
        $logMessage = $exception->getMessage() ?: get_class($exception);
        $this->logger->error(
            $logMessage,
            [
                'request' => $data,
                'trace' => debug_backtrace()
            ]
        );
        if ($exception instanceof AccessDeniedException) {
            return $this->forbidden($request, $exception);
        }
        if ($exception instanceof AuthenticationException) {
            return $this->unauthorized($request, $exception);
        }
        if ($exception instanceof NotFoundException) {
            return $this->notFound($request, $exception);
        }
        if ($exception instanceof ResourceNotFoundException) {
            return $this->notFound($request, $exception);
        }
        if ($exception instanceof NotFoundHttpException) {
            return $this->notFound($request, $exception);
        }
        if ($exception instanceof InvalidConfigException) {
            return $this->commonRender('Config error', $exception->getMessage(), $exception);
        }
        return $this->commonRender('Error!', $exception->getMessage(), $exception);
    }

    protected function commonRender(
        string $title,
        string $message,
        \Throwable $exception,
        int $statusCode = 500
    ): Response {
        $params = [
            'title' => $title,
            'message' => $message,
        ];
        if (EnvHelper::isDebug()) {
            $params['exception'] = $exception;
        }

        $content = "<h1>{$params['title']}</h1>";
        $content .= "
            <div class=\"alert alert-danger\" role=\"alert\">
              {$params['message']}
            </div>";

        if (getenv('APP_DEBUG') && isset($exception)) {
            $trace = new Trace($exception->getTrace());
            $trace->trimFilename(getenv('ROOT_DIRECTORY'));
//            $traceContent = (Represent::trace($trace->getIterator()));
            $traceContent = $exception->getTraceAsString();

            $content .= '<p>Exception class: '. get_class($exception) .'</p>';
            $content .= '<p>File: '. $exception->getFile() .':' . $exception->getLine() .'</p>';

            $content .= "
                <div class=\"alert alert-secondary\" role=\"alert\">
                  <pre><code><p style=\"font-size: 75% !important;\">{$traceContent}</p></code></pre>
                </div>";
        }

        return new Response($content, $statusCode);
    }

    private function notFound(Request $request, Exception $exception): Response
    {
        return $this->commonRender('Not found', 'Page not exists!', $exception, 404);
    }

    private function unauthorized(Request $request, Exception $exception): Response
    {
        if($this->loginUrl) {
            return new RedirectResponse($this->loginUrl, Response::HTTP_FOUND);
        } else {
            return $this->commonRender('Unauthorized', 'Unauthorized', $exception, 401);
        }
    }

    private function forbidden(Request $request, Exception $exception): Response
    {
        return $this->commonRender('Forbidden', 'Access error', $exception, 403);
    }
}
