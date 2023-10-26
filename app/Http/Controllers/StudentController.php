<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\CourseTaken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{

    public function becomeInstructor(Request $request)
    {

        $validator = validator(request()->all(), [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'contact_email' => ['required', 'email'],
            'telephone' => ['required'],
            'paypal_id' => ['required', 'email'],
            'biography' => ['required'],
        ]);

        // Logger(request()->all());

        if ($validator->fails()) {
            // return $validator->getMessageBag();
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 422);
        }


        $instructor = new Instructor();

        $instructor->user_id = Auth::user()->id;
        $instructor->first_name = $request->input('first_name');
        $instructor->last_name = $request->input('last_name');
        $instructor->contact_email = $request->input('contact_email');


        $instructor->telephone = $request->input('telephone');
        $instructor->paypal_id = $request->input('paypal_id');
        $instructor->biography = $request->input('biography');
        $instructor->instructor_image = 'default-image.jpg';
        $instructor->save();

        $user = auth()->user();

        $role = Role::where('name', 'instructor')->first();
        $user->roles()->attach($role);

        return response()->json([
            'status' => true,
            'message' => 'Successfully',
            'instructor' => $instructor,
            'user' => $user->loadMissing('roles')
        ], 200);
    }
    public function myCourses(Request $request)
    {

        $user = Auth::user();
        $user->load([
            'courses' => function ($query) {
                $query->where('is_active', 1);
            },
        ]);
        // courses;

        $user->courses->load([
            'category',
            'instructor'
        ]);

        return $user->courses;
    }

    function isEnroll($course_id)
    {
        $course_taken = CourseTaken::where('course_id', $course_id)
            ->where('user_id', auth()->user()->id)->get()->first();

        // Logger($course_taken);

        if ($course_taken) {
            return true;
        } else {
            return false;
        }
    }

    function freeEnroll()
    {
        $course_id = request()->input('course_id');
        $course = Course::find($course_id);
        if ($course->price == 0) {
            $courseTaken = new CourseTaken();
            $courseTaken->user_id = auth()->user()->id;
            $courseTaken->course_id = $course_id;
            $courseTaken->save();

            return [
                'status' => true
            ];
        } else {
            return [
                'status' => false
            ];
        }
    }
}
