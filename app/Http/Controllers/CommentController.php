<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function lastest($course_id){
        $course = Course::find($course_id);
        $comments = $course->latestComments;
        $comments->loadMissing('user');
        // $comments->load(['user']);
        // Logger('eager');
        return $comments;
    }
    public function all($course_id){
        $course = Course::find($course_id);
        $cmts = $course->comments;
        $cmts->loadMissing('user');
        return $cmts;
    }
    public function save(){
        $course_id = request('course_id');
        $content = request('content');
        $course = Course::find($course_id);
        $course->comment($content);

        $cmts = $course->latestComments;
        $cmts->loadMissing('user');
        return $cmts;
    }
}
