<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request and enforce security headers.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Remove version & technology exposure from PHP response
        if (function_exists('header_remove')) {
            header_remove('X-Powered-By');
            header_remove('Server');
        }

        /** @var Response $response */
        $response = $next($request);

        // Remove technology disclosure headers from Symfony response
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        // 1. Clickjacking Protection: Prevent site from being embedded into iframes on external domains
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // 2. MIME Sniffing Protection
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // 3. XSS Filter Protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // 4. Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 5. CSP Frame Ancestors for modern Clickjacking defense
        $existingCsp = $response->headers->get('Content-Security-Policy');
        if (!$existingCsp) {
            $response->headers->set('Content-Security-Policy', "frame-ancestors 'self';");
        }

        // 6. Restrict sensitive browser features
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        return $response;
    }
}
