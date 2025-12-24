<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MinifyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        if ($response instanceof Response && str_contains($response->headers->get('Content-Type', ''), 'text/html'))
        {
            $content = $response->getContent();
            $content = preg_replace([
                '/>\s+</', // remove whitespace between tags
                '/<!--(?!\[if).*?-->/s', // remove normal HTML comments except conditional IE comments
                '/\/\*.*?\*\//s', // remove CSS/JS comments
                '/\s{2,}/', // reduce multiple spaces, tabs, newlines
            ], [
                '><', '', '', ' '
            ], $content);
            $response->setContent($content);
        }
        return $response;
    }
}
