<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenAbility
{
    /**
     * Handle an incoming request - Check for admin:* ability
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Check if user is authenticated via Sanctum
        if (!$user || !$user->currentAccessToken()) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // Check if token has admin:* ability
        $abilities = $user->currentAccessToken()->abilities;
        
        if (!in_array('admin:*', $abilities)) {
            return response()->json([
                'message' => 'Insufficient permissions. Admin access required.',
                'your_abilities' => $abilities
            ], 403);
        }

        return $next($request);
    }
}
