<?php

namespace App\Http\Middleware;

use App\Models\CourseTaken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Logger;

class IsSubscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Logger('role mid');
        $user = Auth::user();
        $course_id = request('course_id');
        $courseTaken = CourseTaken::where('user_id', $user->id)
        ->where('course_id', $course_id)
        ->first();

        if($courseTaken){
            return $next($request);
        }

        return response()->json([
            'status' => false,
            'message' => 'Unauthorized',
        ], 401);
    }

}
