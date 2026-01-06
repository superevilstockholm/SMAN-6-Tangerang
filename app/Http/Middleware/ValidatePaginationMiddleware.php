<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidatePaginationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('limit')) {
            $limit = $request->query('limit');
            $query = $request->query();
            unset($query['limit']);
            if ($limit !== 'all' && (!is_numeric($limit) || (int) $limit < 1)) {
                return redirect()
                    ->to($request->url() . '?' . http_build_query($query))
                    ->with('error', 'Parameter pagination tidak valid.');
            }
        }
        return $next($request);
    }
}
