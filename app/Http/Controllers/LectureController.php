<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use Illuminate\Http\Request;

class LectureController extends Controller
{
    function save(Request $request)
    {
        $lecture_id = $request->input('lecture_id');
        $section_id = $request->input('section_id');
        $description = $request->input('description');
        $title = $request->input('title');
        // $sort_order = $request->input('sort_order');

        // create mode
        if ($lecture_id == null) {
            $lecture = new Lecture();
            $lecture->section_id = $section_id;

            // $lecture->sort_order = $sort_order;
        } else {
            $lecture = Lecture::find($lecture_id);
        }
        $lecture->description = $description;
        $lecture->title = $title;


        $lecture->save();
        $lecture->lecture_content;
        return $lecture;
    }

    public function delete()
    {
        $lecture = Lecture::find(request('lecture_id'));
        // Logger($lecture);
        if ($lecture) {
            $lecture->delete();
        }
    }
}
