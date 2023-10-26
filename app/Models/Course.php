<?php

namespace App\Models;

use Logger;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use willvincent\Rateable\Rateable;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Log\Logger as LogLogger;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;
    use Rateable;

    function instruction_level()
    {
        return $this->belongsTo(InstructionLevel::class);
    }

    function instructor()
    {
        return $this->belongsTo(Instructor::class)
            ->select('id', 'first_name', 'last_name', 'paypal_id');
    }
    function course_takens()
    {
        return $this->hasMany(CourseTaken::class);
    }
    function category()
    {
        return $this->belongsTo(Category::class);
    }
    function sections()
    {
        return $this->hasMany(Section::class);
    }
    function latestComments()
    {
        return $this->hasMany(Comment::class)->latest()->limit(7);
    }
    function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
    function comment($content)
    {
        $comment = new Comment();
        $comment->content = $content;
        $comment->course_id = $this->id;
        $comment->user_id = auth()->user()->id;
        $comment->save();
    }

    public function getAuthUserRating()
    {
        return $this->ratings()->where('user_id', auth()->user()->id)->get()->first();
    }

    public static function deleteCourseImg($course)
    {
            $filename = $course->course_image;
            if($filename == 'default-image.jpg') return;

            $path = 'public/course_images/' .  $filename;
            if (Storage::exists($path)) {
                Storage::delete($path);
            }

    }


    protected static function booted()
    {
        static::deleting(function (Course $course) {

            Course::deleteCourseImg($course);


            $course->course_takens()->each(function ($taken) {
                $taken->delete();
            });
            $course->ratings()->each(function ($rating) {
                $rating->delete();
            });
            $course->sections()->each(function ($section) {
                $section->delete();
            });
            $course->comments()->each(function ($cmt) {
                $cmt->delete();
            });
        });
    }









}
