<?php

declare(strict_types=1);

namespace Snappy\Core\Router;

use League\Route\Http\Exception\MethodNotAllowedException;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Strategy\ApplicationStrategy;
use Psr\Http\Server\MiddlewareInterface;
use Snappy\Core\ErrorHandler\ErrorHandlerInterface;
use Snappy\Core\Router\Middleware\ErrorHandlerMiddleware;
use Snappy\Core\Router\Middleware\MethodNotAllowedMiddleware;
use Snappy\Core\Router\Middleware\NotFoundMiddleware;

class ErrorHandlerStrategy extends ApplicationStrategy
{
    private ErrorHandlerInterface $errorHandler;

    public function __construct(ErrorHandlerInterface $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    public function getMethodNotAllowedDecorator(MethodNotAllowedException $exception): MiddlewareInterface
    {
        return new MethodNotAllowedMiddleware($this->errorHandler, $exception);
    }

    public function getNotFoundDecorator(NotFoundException $exception): MiddlewareInterface
    {
        return new NotFoundMiddleware($this->errorHandler, $exception);
    }

    public function getThrowableHandler(): MiddlewareInterface
    {
        return new ErrorHandlerMiddleware($this->errorHandler);
    }
}