<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class EnsureUserIsAdmin
 * 
 * This middleware ensures that the user making the request is an admin.
 */
class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = JWTAuth::user();
        // Check if the authenticated user is not an admin
        if ($user && ($user->is_admin == 'true') ){
            return $next($request);        }
            return response()->json(['message' => 'Unauthorized'], 403);

    }
}
