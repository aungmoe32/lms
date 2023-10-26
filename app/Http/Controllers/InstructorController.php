<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Course;
use App\Models\Instructor;
use Illuminate\Log\Logger;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\InstructionLevel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InstructorController extends Controller
{

    private $defaultImage = 'default-image.jpg';

    function profile()
    {
        $instructor = auth()->user()->instructor;
        // Logger($instructor);
        return $instructor->toArray();
    }
    function instructor($id)
    {
        $instructor = Instructor::find($id);

        $instructor->makeHidden(['user_id']);

        $instructor->load([
            'latestCourses',
        ]);
        $instructor->loadCount([
            'courses'
        ]);

        return $instructor;
    }

    function profileSave()
    {
        $instructor = auth()->user()->instructor;

        $instructor->first_name = request('first_name');
        $instructor->last_name = request('last_name');
        $instructor->contact_email = request('contact_email');
        $instructor->paypal_id = request('paypal_id');
        $instructor->telephone = request('telephone');
        $instructor->biography = request('biography');

        $instructor->mobile = strval(request('mobile'));
        $instructor->link_facebook = strval(request('link_facebook'));
        $instructor->link_linkedin = strval(request('link_linkedin'));
        $instructor->link_twitter = strval(request('link_twitter'));
        $instructor->link_googleplus = strval(request('link_googleplus'));



        $image = request()->file('instructor_image');

        // Logger(request()->file());

        if ($image) {
            // Logger('ima');
            $path = 'public/instructor_images/' . $instructor->instructor_image;

            if (!($instructor->instructor_image == $this->defaultImage )&& Storage::exists($path)) {
                // Logger('exist');
                Storage::delete($path);
            }

            $org_name = $image->getClientOriginalName();
            $filename = uniqid() . '_' . preg_replace('!\s+!', '_', $org_name);

            $image->storeAs('public/instructor_images', $filename);

            $instructor->instructor_image = $filename;

        }

        $instructor->save();


        return $instructor;
    }
    function instructors()
    {
        $instructors = Instructor::all();
        $instructors->makeHidden(['user_id']);

        return $instructors;
    }


    function metrics()
    {
        $instructor_id = Auth::user()->instructor->id;
        $metrics = Instructor::metrics($instructor_id);


        // $instructor = Instructor::find($instructor_id);
        // $instructor->loadCount('courses');
        // $instructor->courses->loadCount('sections');

        // return $metrics;
        return response()->json([
            'status' => true,
            'message' => '',
            'metrics' => $metrics,
        ], 200);
    }

    function courseCurriculum(){
        $course_id = request('course_id');
        $course = Course::find($course_id);

        $course->load([
            'sections.lectures.lecture_content',
        ]);

        return $course;

    }


    function instructorCourseList()
    {


        $instructor_id = Auth::user()->instructor->id;
        $courses = DB::table('courses')
            ->select('courses.*', 'categories.name as category_name')
            ->leftJoin('categories', 'categories.id', '=', 'courses.category_id')
            ->where('courses.instructor_id', $instructor_id)
            // ->paginate($paginate_count);
            ->get();

        return response()->json([
            'status' => true,
            'message' => '',
            'courses' => $courses,
        ], 200);
    }

    public function instructorCourseInfo(Request $request, $course_id = '')
    {

        $course = null;
        if ($course_id) {
            $course = Course::find($course_id);
        }

        $levels = InstructionLevel::get();

        return response()->json([
            'status' => true,
            'message' => '',
            'course' => $course,
            'instruction_levels' => $levels,
        ], 200);
    }

    function instructorCourseDelete(Request $request, $course_id)
    {
        Course::destroy($course_id);

        return response()->json([
            'status' => true,
            'message' => 'Deleted Successfully',
        ], 200);
    }

    function courseImageSave(Request $request)
    {
        $course_id = request('course_id');
        $course = Course::find($course_id);
        $image = $request->file('image');

        $path = 'public/course_images/' . $course->course_image;

        if (!($course->course_image == 'default-image.jpg') && Storage::exists($path)) {
            // Logger('exist');
            Storage::delete($path);
        }
        // if (Storage::exists($path)) {
        //     // Logger('exist');
        //     Storage::delete($path);
        // }

        $org_name = $image->getClientOriginalName();
        $filename = uniqid() . '_' . preg_replace('!\s+!', '_', $org_name);

        // $image_path = '/storage/course/' . $course_id . '/' . $filename;
        // $image->storeAs('public/course/' . $course_id, $filename);

        $image_path = '/storage/course_images/' .  $filename;
        $image->storeAs('public/course_images', $filename);

        $course->course_image = $filename;
        $course->save();

        return $image_path;
    }

    function isMyCourse($course)
    {
        return auth()->user()->instructor->id == $course->instructor_id;
    }

    public function instructorCourseInfoSave(Request $request)
    {


        $validation_rules = [
            'course_title' => 'required|string|max:50',
            'category_id' => 'required',
            'instruction_level_id' => 'required',
            'overview' => 'required',
            'is_active' => 'required',
        ];

        $validator = Validator::make($request->all(), $validation_rules);

        // Stop if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $course_id = $request->input('course_id');


        if ($course_id) {
            $course = Course::find($course_id);
            $success_message = 'Course updated successfully';

            if ($course) {
                if (!$this->isMyCourse($course)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'unauthorized',
                    ], 401);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'course not found!',
                ], 422);
            }
        } else {
            $course = new Course();
            $success_message = 'Course added successfully';
        }




        $course->course_title = $request->input('course_title');
        $course->instructor_id = Auth::user()->instructor->id;
        $course->category_id = $request->input('category_id');
        $course->instruction_level_id = $request->input('instruction_level_id');
        $course->overview = $request->input('overview');

        $course->duration = $request->input('duration');
        $course->price = $request->input('price');

        $course->is_active = $request->input('is_active');
        $course->course_image = 'default-image.jpg';
        $course->save();

        $course_id = $course->id;

        return response()->json([
            'status' => true,
            'message' => $success_message,
            'course' => $course
        ], 200);
    }

}
