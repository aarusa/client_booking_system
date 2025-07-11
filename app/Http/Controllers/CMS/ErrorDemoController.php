<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class ErrorDemoController extends Controller
{
    /**
     * Demonstrate different error scenarios
     */
    public function demo(Request $request, $type = '500')
    {
        switch ($type) {
            case '404':
                throw new ModelNotFoundException('Resource not found');
                
            case '403':
                throw new AuthorizationException('Access denied');
                
            case '401':
                abort(401, 'Unauthorized access');
                
            case '419':
                throw new TokenMismatchException('Session expired');
                
            case '429':
                throw new ThrottleRequestsException('Too many requests');
                
            case '422':
                return response()->view('cms.error', [
                    'errorCode' => 422,
                    'errorTitle' => 'Validation Error',
                    'errorMessage' => 'The provided data is invalid. Please check your input and try again.',
                    'autoRedirect' => false,
                    'redirectUrl' => null
                ], 422);
                
            case '503':
                return response()->view('cms.error', [
                    'errorCode' => 503,
                    'errorTitle' => 'Service Unavailable',
                    'errorMessage' => 'We\'re temporarily unavailable for maintenance. Please try again later.',
                    'autoRedirect' => true,
                    'redirectUrl' => route('dashboard.index')
                ], 503);
                
            case '400':
                abort(400, 'Bad request');
                
            default:
                abort(500, 'Internal server error');
        }
    }
} 