<?php

namespace App\Http\Middleware;

use Logger;
use Closure;
use App\Models\Course;
use App\Models\CourseTaken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class IsCourseOwner
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
        // Logger('owner mid');
        $user = Auth::user();
        $course_id = request('course_id');
        // Logger($course_id == null);
        if($course_id == null && Route::current()->getName() == 'instructor.course-info-save'){
            return $next($request);
        }

        $course = Course::find($course_id);



        if($user->instructor->id == $course->instructor_id){
            return $next($request);
        }

        return response()->json([
            'status' => false,
            'message' => 'Unauthorized',
        ], 401);
    }

}
