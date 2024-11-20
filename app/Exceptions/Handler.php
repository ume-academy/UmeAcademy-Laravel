<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function shouldReturnJson($request, Throwable $e)
    {
        return true;
    }

    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {
            // Xử lý lỗi 404
            if ($exception instanceof NotFoundHttpException) {
                return response()->json([
                    'error' => 'Route not found.',
                    'message' => 'The API endpoint you are trying to access does not exist.',
                ], 404);
            }
    
            // Xử lý lỗi 405 Method Not Allowed
            if ($exception instanceof MethodNotAllowedHttpException) {
                return response()->json([
                    'error' => 'Method not allowed.',
                    'message' => 'The HTTP method used for this request is not allowed on this route.',
                ], 405);
            }
        }

        return parent::render($request, $exception);
    }
}
