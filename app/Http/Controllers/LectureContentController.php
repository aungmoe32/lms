<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\LectureContent;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;

class LectureContentController extends Controller
{
    public function save(Request $request)
    {
        $lecture_id = $request->input('lecture_id');
        // $content_id = $request->input('content_id');
        // $content_id = request('content_id');
        $type = $request->input('type');
        $course_id = $request->input('course_id');

        // Logger(is_string($content_id));
        $lecture = Lecture::find($lecture_id);
        $content = $lecture->lecture_content;
        if($content == null){
            $content = new LectureContent();
            $content->lecture_id = $lecture_id;
        }
        $content->type = $type;
        if($type == 'text'){
            $text = $request->input('text');
            $content->contenttext = $text;
        }
        else{
            Lecture::deleteFile($lecture);
            $file = request()->file('file');
            $org_filename = $file->getClientOriginalName();
            $org_extension = $file->getClientOriginalExtension();
            $filename = uniqid() . '_' . preg_replace('!\s+!', '_', $org_filename);
            $file->storeAs('public/course/' . $course_id, $filename);

            $content->file_name = $filename;
            $content->file_title = $org_filename;
            $content->file_extension = $org_extension;
        }

        $content->instructor_id = auth()->user()->instructor->id;
        // $content->instructor_id = 0;

        $content->save();

        // Logger($lecture->lecture_content);
        $lecture->load('lecture_content');
        return $lecture->lecture_content;
        // return response($lecture->lecture_content, 200)
        // ->header('Content-Type', 'application/json');



    }


    // public function delete(){
    //     $content = LectureContent::find(request('content_id'));
    //     if($content){
    //         $content->delete();
    //     }
    // }
}
