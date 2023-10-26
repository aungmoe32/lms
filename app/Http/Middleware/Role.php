<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Logger;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Logger('role mid');
        $user = Auth::user();

        foreach($roles as $role) {
            // Check if user has the role This check will depend on how your roles are set up
            if($user->hasRole($role))
                return $next($request);
        }

        return response()->json([
            'status' => false,
            'message' => 'Unauthorized',
        ], 401);
    }

}
