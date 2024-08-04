<?php

use App\Helpers\APIHelper;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    api: __DIR__ . '/../routes/api.php',
    commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(function (Request $request, $e) {
            return $request->wantsJson() || $request->expectsJson();
        });

        $exceptions->respond(function (Response $response) {
            if (json_decode($response->getContent())) {
                $message = json_decode($response->getContent())->message ?? null;
                $errors = json_decode($response->getContent())->errors ?? null;
                return APIHelper::error(
                    $message,
                    $errors ?? null,
                    $response->getStatusCode()
                );
            }
            return $response;
        });
    })->create();
