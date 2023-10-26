<?php

namespace App\Models;

use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;



    function user()
    {
        return $this->belongsTo(User::class)
        ->select('id', 'first_name', 'last_name');;
    }

    function course()
    {
        return $this->belongsTo(Course::class);
    }
}
