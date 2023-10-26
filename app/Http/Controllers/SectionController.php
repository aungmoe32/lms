<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function save(Request $request)
    {
        $section_id = $request->input('section_id');
        $course_id = $request->input('course_id');
        $title = $request->input('title');


        if ($section_id == null) {
            $section = new Section();
            $section->course_id = $course_id;
        } else {
            $section = Section::find($section_id);
        }

        $section->title = $title;


        $section->save();
        $section->lectures;
        return $section;
    }


    public function delete()
    {
        $section = Section::find(request('section_id'));
        if ($section) {
            $section->delete();
        }
    }
}
