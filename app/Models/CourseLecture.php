<?php

namespace App\Models;

use Illuminate\Log\Logger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseLecture extends Model
{
    use HasFactory;

    protected $table = 'curriculum_lectures_quiz';
    protected $primaryKey = 'lecture_quiz_id';
    // protected $with = ['file'];

    function file()
    {
        return $this->morphTo(__FUNCTION__, 'media_type', 'media');
        // return $this->morphTo(null, 'media_type', 'media');
    }




    function section()
    {
        return $this->hasOne(CourseSection::class, 'section_id', 'section_id');
    }

    function progress()
    {
        return $this->hasOne(CourseProgress::class, 'lecture_id', 'lecture_quiz_id');
    }


    public static function deleteFile($lecture)
    {
        if ($lecture->file) {
            $course_id = $lecture->section->course->id;
            if ($lecture->media_type == 0) {
                $filename = $lecture->file->video_title;
            } else {
                $filename = $lecture->file->file_name;
            }

            $path = 'public/course/' . $course_id . '/' . $filename;
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
        }
    }

    // this is a recommended way to declare event handlers
    protected static function booted()
    {

        static::deleting(function (CourseLecture $lecture) { // before delete() method call this

            CourseLecture::deleteFile($lecture);

            if ($lecture->file) {
                $lecture->file->delete();
            }

            // do the rest of the cleanup...
        });
    }
}
