<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseFile extends Model
{
    protected $table 		= 'course_files';
    use HasFactory;

    public function lecture()
    {
        return $this->morphOne(CourseLecture::class, 'file');
    }

}


