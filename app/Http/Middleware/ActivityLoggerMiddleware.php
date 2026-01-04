<?php

namespace App\Http\Middleware;

use Closure;
use Throwable;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

// Models
use App\Models\Settings\ActivityLog;

class ActivityLoggerMiddleware
{
    protected function shouldLog(Request $request): bool
    {
        return in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE']);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        try {
            if ($this->shouldLog($request)) {
                ActivityLog::create([
                    'method' => $request->method(),
                    'path' => $request->path(),
                    'user_id' => optional($request->user())->id,
                    'route_name' => optional($request->route())->getName(),
                    'ip_address' => $request->ip(),
                    'user_agent' => Str::limit($request->userAgent() ?? '', 1000),
                    'status_code' => $response->getStatusCode(),
                ]);
            }
        } catch (Throwable $e) {
            Log::error('Failed to log request: ' . $e->getMessage());
        }
        return $response;
    }
}
