<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Custom error handling for all exceptions
        $this->renderable(function (Throwable $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => $this->getErrorTitle($e),
                    'message' => $this->getErrorMessage($e),
                    'code' => $this->getErrorCode($e)
                ], $this->getErrorCode($e));
            }

            return $this->renderErrorPage($e, $request);
        });
    }

    /**
     * Render the custom error page
     */
    private function renderErrorPage(Throwable $e, $request)
    {
        $errorCode = $this->getErrorCode($e);
        $errorTitle = $this->getErrorTitle($e);
        $errorMessage = $this->getErrorMessage($e);
        
        // Determine if we should auto-redirect
        $autoRedirect = in_array($errorCode, [419, 503]); // Session expired or maintenance
        $redirectUrl = $autoRedirect ? route('login') : null;

        return response()->view('cms.error', [
            'errorCode' => $errorCode,
            'errorTitle' => $errorTitle,
            'errorMessage' => $errorMessage,
            'autoRedirect' => $autoRedirect,
            'redirectUrl' => $redirectUrl
        ], $errorCode);
    }

    /**
     * Get error code
     */
    private function getErrorCode(Throwable $e)
    {
        if (method_exists($e, 'getStatusCode')) {
            return $e->getStatusCode();
        }

        if ($e instanceof \Illuminate\Auth\AuthenticationException) {
            return 401;
        }

        if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
            return 403;
        }

        if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return 404;
        }

        if ($e instanceof \Illuminate\Validation\ValidationException) {
            return 422;
        }

        if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return 404;
        }

        if ($e instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException) {
            return 403;
        }

        if ($e instanceof \Illuminate\Session\TokenMismatchException) {
            return 419;
        }

        if ($e instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException) {
            return 429;
        }

        return 500;
    }

    /**
     * Get error title
     */
    private function getErrorTitle(Throwable $e)
    {
        $code = $this->getErrorCode($e);
        
        $titles = [
            400 => 'Bad Request',
            401 => 'Unauthorized Access',
            403 => 'Access Denied',
            404 => 'Page Not Found',
            419 => 'Session Expired',
            422 => 'Validation Error',
            429 => 'Too Many Requests',
            500 => 'Internal Server Error',
            503 => 'Service Unavailable'
        ];

        return $titles[$code] ?? 'Something Went Wrong';
    }

    /**
     * Get error message
     */
    private function getErrorMessage(Throwable $e)
    {
        $code = $this->getErrorCode($e);
        
        // Custom messages based on error type
        if ($e instanceof \Illuminate\Auth\AuthenticationException) {
            return 'Please log in to access this page.';
        }

        if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
            return 'You don\'t have permission to access this resource.';
        }

        if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return 'The requested resource could not be found.';
        }

        if ($e instanceof \Illuminate\Session\TokenMismatchException) {
            return 'Your session has expired. Please refresh the page and try again.';
        }

        if ($e instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException) {
            return 'You\'re making too many requests. Please wait a moment and try again.';
        }

        // Default messages based on error code
        $messages = [
            400 => 'The request could not be processed. Please check your input and try again.',
            401 => 'You need to be logged in to access this page.',
            403 => 'You don\'t have the necessary permissions to access this resource.',
            404 => 'The page you\'re looking for doesn\'t exist or has been moved.',
            419 => 'Your session has expired. Please log in again.',
            422 => 'The provided data is invalid. Please check your input and try again.',
            429 => 'You\'ve made too many requests. Please wait a moment before trying again.',
            500 => 'Something went wrong on our servers. We\'re working to fix the issue.',
            503 => 'We\'re temporarily unavailable for maintenance. Please try again later.'
        ];

        return $messages[$code] ?? 'An unexpected error occurred. Please try again later.';
    }
}
