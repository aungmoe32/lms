<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function save(){
        $course_id = request('course_id');
        $value = request('rating');
        $course = Course::find($course_id);
        $course->rateOnce($value);
        return $course->getAuthUserRating();
    }

    public function userRatings($course_id){
        $course = Course::find($course_id);
        return $course->getAuthUserRating();
    }



}
