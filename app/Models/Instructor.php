<?php

namespace App\Models;

use Hamcrest\Core\Set;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Instructor extends Model
{
    use HasFactory;


    function courses()
    {
        return $this->hasMany(Course::class)->where('is_active', 1);
    }
    function latestCourses()
    {
        return $this->hasMany(Course::class)->latest()->limit(5)->where('is_active', 1);
    }


    public static function metrics($instructor_id)
    {
        $metrics = array();
        $metrics['courses'] = DB::table('courses')->where('instructor_id', $instructor_id)->count();
        $metrics['sections'] = DB::table('courses')
            ->where('courses.instructor_id', $instructor_id)
            ->rightJoin('sections', 'sections.course_id', '=', 'courses.id')
            ->count();


        $metrics['lectures'] = DB::table('courses')
            ->where('courses.instructor_id', $instructor_id)
            ->rightJoin('sections', 'sections.course_id', '=', 'courses.id')
            ->rightJoin('lectures', 'lectures.section_id', '=', 'sections.id')
            ->count();

        return $metrics;
    }

    protected static function booted()
    {
        static::deleting(function (Instructor $instructor) {

            $instructor->courses()->each(function ($course) {
                $course->delete();
            });
        });
    }
}
