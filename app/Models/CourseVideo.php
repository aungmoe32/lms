<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseVideo extends Model
{
    use HasFactory;

    protected $table = 'course_videos';

    public function lecture()
    {
        return $this->morphOne(CourseLecture::class, 'file', 'media_type', 'media');
    }
}
