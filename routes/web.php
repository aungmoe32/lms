<?php

use App\Models\Course;
use App\Models\CourseLecture;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Jenssegers\Agent\Facades\Agent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\LectureContentController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SectionController;
use App\Models\CourseFile;
use App\Models\CourseVideo;
use App\Models\Instructor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;



Route::get('/save/{course_id}', [InstructorController::class, 'courseCurriculum']);
Route::get('/rate/{id}', function () {
    // $course = Course::first();
    // echo  request('id');

});


Route::get('/payment', function () {
    return view('paypal');
});

Route::get('/checkout', [PaymentController::class, 'checkout']);
Route::get('/payment-success', [PaymentController::class, 'paymentSuccess']);


Route::fallback(function () {
    return view('layouts.master');
});
