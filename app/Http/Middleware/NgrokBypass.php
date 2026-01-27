<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NgrokBypass
{
    /**
     * Handle an incoming request.
     * 
     * Bypass ngrok browser warning by adding the required header to all responses.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Add header to bypass ngrok's browser warning page
        $response->headers->set('ngrok-skip-browser-warning', 'true');
        
        return $response;
    }
}
