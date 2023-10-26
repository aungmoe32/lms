<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSection extends Model
{
    use HasFactory;
    protected $table = 'curriculum_sections';
    protected $primaryKey = 'section_id';


    function lectures()
    {
        return $this->hasMany(CourseLecture::class, 'section_id');
    }

    function course()
    {
        return $this->hasOne(Course::class, 'id', 'course_id');
    }


    protected static function booted () {
        static::deleting(function(CourseSection $section) {
            // $section->lectures()->delete();
            $section->lectures()->each(function($lecture) {
                $lecture->delete(); // <-- direct deletion
             });

        });
    }

}
