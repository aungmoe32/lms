<?php

namespace App\Models;

use App\Models\Lecture;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Random\Engine\Secure;

class Section extends Model
{
    use HasFactory;

    function lectures()
    {
        return $this->hasMany(Lecture::class);
    }

    function course()
    {
        return $this->hasOne(Course::class, 'id', 'course_id');
    }


    protected static function booted () {
        static::deleting(function(Section $section) {
            // $section->lectures()->delete();
            $section->lectures()->each(function($lecture) {
                $lecture->delete(); // <-- direct deletion
             });

        });
    }
}
