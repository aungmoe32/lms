<?php

namespace App\Models;

use App\Models\Lecture;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LectureContent extends Model
{
    use HasFactory;

    function lecture(){
        return $this->belongsTo(Lecture::class);

    }
}
