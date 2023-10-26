<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseProgress extends Model
{
    use HasFactory;
    protected $fillable = [
        'status'
    ];
    protected $table = 'course_progress';


}
