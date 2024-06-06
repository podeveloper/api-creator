<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        apiPrefix: '/api/v1',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Exception $exception) {
            $status = match (true) {
                $exception instanceof UnauthorizedException => JsonResponse::HTTP_UNAUTHORIZED,
                $exception instanceof ValidationException => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                $exception instanceof MethodNotAllowedHttpException => JsonResponse::HTTP_METHOD_NOT_ALLOWED,
                $exception instanceof NotFoundHttpException => JsonResponse::HTTP_NOT_FOUND,
                default => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
            };

            $response = [
                'status' => 'error',
                'message' => $exception->getMessage(),
            ];

            if ($exception instanceof ValidationException) {
                $response['errors'] = $exception->errors();
            }

            return response()->json($response, $status);
        });
    })->create();
