<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditTrail
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true) && $request->user()) {
            AuditLog::query()->create([
                'user_id' => $request->user()->id,
                'action' => $request->method().' '.$request->path(),
                'entity' => $request->route()?->getName(),
                'entity_id' => null,
                'meta' => [
                    'ip' => $request->ip(),
                    'user_agent' => substr((string) $request->userAgent(), 0, 255),
                ],
            ]);
        }

        return $response;
    }
}
