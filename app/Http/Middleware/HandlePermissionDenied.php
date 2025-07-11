<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Access\AuthorizationException;

class HandlePermissionDenied
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (AuthorizationException $e) {
            // If it's an AJAX request, return JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Access denied',
                    'message' => 'You do not have permission to access this resource.',
                    'code' => 403
                ], 403);
            }

            // For regular requests, redirect to custom error page
            return response()->view('cms.error', [
                'errorCode' => 403,
                'errorTitle' => 'Access Denied',
                'errorMessage' => 'You do not have permission to access this page. Please contact your administrator if you believe this is an error.',
                'autoRedirect' => false,
                'redirectUrl' => null
            ], 403);
        }
    }
} 