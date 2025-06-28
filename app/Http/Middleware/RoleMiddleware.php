<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Traits\ApiResponseTrait;

class RoleMiddleware
{
    use ApiResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            if ($this->isApiRequest($request)) {
                return $this->apiError('Unauthenticated', null, 401);
            }
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        if (!in_array($user->role, $roles)) {
            if ($this->isApiRequest($request)) {
                return $this->apiError('Access denied. Insufficient permissions.', null, 403);
            }
            abort(403, 'Access denied. Insufficient permissions.');
        }

        return $next($request);
    }
}
