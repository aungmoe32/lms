<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lecture extends Model
{
    use HasFactory;

    function section()
    {
        return $this->belongsTo(Section::class);
    }


    function lecture_content()
    {
        return $this->hasOne(LectureContent::class);
    }



    function progress()
    {
        return $this->hasOne(CourseProgress::class);
    }


    function progresses()
    {
        return $this->hasMany(CourseProgress::class);
    }


    public static function deleteFile($lecture)
    {
        if ($lecture->lecture_content == null) return;
        $content = $lecture->lecture_content;
        if ($content->type != 'text') {
            $course_id = $lecture->section->course->id;
            $filename = $content->file_name;
            $path = 'public/course/' . $course_id . '/' . $filename;
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
        }
    }

    // this is a recommended way to declare event handlers
    protected static function booted()
    {

        static::deleting(function (Lecture $lecture) { // before delete() method call this


            Lecture::deleteFile($lecture);
            if ($lecture->lecture_content) {
                $lecture->lecture_content->delete();
            }



            $lecture->progresses()->each(function ($progress) {
                $progress->delete(); // <-- direct deletion
            });
        });
    }
}
