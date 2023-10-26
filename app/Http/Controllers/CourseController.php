<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseFile;
use App\Models\CourseLecture;
use App\Models\CourseProgress;
use App\Models\CourseTaken;
use App\Models\CourseVideo;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\InstructionLevel;
use App\Models\Lecture;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PDO;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->model = new Course();
    }





    function isMyCourse($course)
    {
        return auth()->user()->instructor->id == $course->instructor_id;
    }

    public static function getEloquentSqlWithBindings($query)
    {
        return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());
    }

    function courses(Request $request)
    {
        $search = request('search');
        $category_search = $request->input('category_id');
        $prices = $request->input('price_id');
        $instruction_level_id = $request->input('instruction_level_id');

        // dd($category_search);

        $query = Course::query();

        //

        if ($category_search) {
            $query->whereIn('courses.category_id', $category_search);
        }

        if ($instruction_level_id) {
            $query->whereIn('courses.instruction_level_id', $instruction_level_id);
        }

        if ($prices) {

            if (in_array('500', $prices)) {
                $query->where('courses.price', '>=', 500);
            } else {

                if (count($prices) == 1) {
                    $price_split = explode('-', $prices[0]);
                    $start = $price_split[0];
                    $end = $price_split[1];
                } else {
                    $price_split = explode('-', $prices[0]);
                    $start = $price_split[0];
                    $price_split = explode('-', end($prices));
                    $end = $price_split[1];
                }
                $query->where('courses.price', '>=', $start);
                $query->where('courses.price', '<=', $end);
            }
        }

        if ($search) {
            $query->where('courses.course_title', 'LIKE', '%' . $search . '%');
        }


        $query
            ->limit(8)
            ->where('is_active', 1)
            ->orderBy('courses.created_at', 'desc');

        $courses = $query->get();
        $courses->load([
            'instructor',
            'category',
        ]);

        return $courses;
    }




    public function courseImage($course_id, Request $request)
    {
        $course = Course::find($course_id);
        if ($course->course_image == null) {
            return '/storage/course_images/default-image.jpg';
        }
        return '/storage/course_images/' . $course->course_image;
    }





    function courseEnrollLecture($course_id, $lecture_id)
    {
        $lecture = Lecture::find($lecture_id);

        // $course->loadMissing('sections.lectures.progress');

        $lecture->load([
            'lecture_content',
            'progress' => function ($query) {
                $query->where('user_id', auth()->user()->id);
            },
        ]);

        // $lecture->file;
        // $course->loadMissing(['sections.lectures.media_file' => function ($query) {
        //     $query->where('course_progress.user_id', auth()->user()->id);
        // }]);

        return $lecture;
    }

    public function updateLectureStatus($course_id = '', $lecture_id = '', $status = '')
    {
        $user_id  = Auth::user()->id;

        $progress = CourseProgress::query()
            ->where("course_id", '=', $course_id)
            ->where("lecture_id", '=', $lecture_id)
            ->where("user_id", '=', $user_id)
            ->first();

        $status = $status == 'true' ? 1 : 0;
        $dataarray = array();
        if ($progress) {
            $progress->update([
                'status' => $status
            ]);
        } else {
            $progress = new CourseProgress();
            $progress->course_id = $course_id;
            $progress->user_id = $user_id;
            $progress->lecture_id = $lecture_id;
            $progress->lecture_id = $lecture_id;
            $progress->status = $status;
            $progress->save();
        }
        return $progress->id;
    }







    function lastestCourses()
    {

        $courses = Course::query()
            ->where('is_active', 1)
            ->limit(8)
            ->orderBy('courses.created_at', 'desc')
            ->get();

        $courses->load([
            'category',
            'instructor'
        ]);

        // dd($latestTab_courses);

        return response()->json([
            'status' => true,
            'message' => '',
            'lastestCourses' => $courses
        ], 200);
    }












    function courseView($course_id)
    {
        return $this->course($course_id, false);
    }

    function courseEnroll($course_id)
    {
        return $this->course($course_id, true);
    }



    function course($course_id, $courseEnroll)
    {

        $course = Course::with([
            'sections.lectures.lecture_content'
            => function ($query) {
                $query->select(['id', 'lecture_id', 'type']);
            },
        ])
        ->find($course_id);

        $course->averageRating = intval($course->averageRating);
        $course->userSumRating = intval($course->usersRated());


        if ($courseEnroll) {
            $course->loadMissing([
                'sections.lectures.progress' => function ($query) {
                    $query->where('course_progress.user_id', auth()->user()->id);
                },
            ]);
        }


        $course->load([
            'instructor',
            'category',
            'instruction_level',
        ]);

        $course->loadCount([
            'course_takens',
        ]);

        return $course;
    }
}
