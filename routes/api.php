<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\LectureContentController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Models\Instructor;
use App\Models\Post;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/






Route::get('/lastest-courses', [CourseController::class, 'lastestCourses']);
Route::get('/courses', [CourseController::class, 'courses']);
Route::get('/instructors', [InstructorController::class, 'instructors']);


Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/active-categories', [CategoryController::class, 'active_categories']);
Route::get('/course-view/{course_id}', [CourseController::class, 'courseView']);
Route::get('/instructors/{id}', [InstructorController::class, 'instructor']);
Route::get('/course-image/{course_id}', [CourseController::class, 'courseImage']);


Route::get('/comments/{id}', [CommentController::class, 'lastest']);
Route::get('/all-comments/{id}', [CommentController::class, 'all']);


Route::group(['middleware' => ['auth']], function () {
    Route::get('/categories', [CategoryController::class, 'categories']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/profile-save', [UserController::class, 'saveProfile']);


    Route::post('/comment-save', [CommentController::class, 'save']);
    Route::post('/rating-save', [RatingController::class, 'save']);
    Route::get('/user-ratings/{id}', [RatingController::class, 'userRatings']);


    // Route::get('/payment-form', [PaymentController::class, 'paymentForm']);


    Route::post('/checkout', [PaymentController::class, 'checkout']);
    Route::get('/payment-success', [PaymentController::class, 'paymentSuccess']);



    Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function () {
        Route::get('/users', [AdminController::class, 'list']);
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::post('/save-user', [AdminController::class, 'saveUser']);
        Route::post('/delete-user', [AdminController::class, 'deleteUser']);

        Route::post('save-category', [CategoryController::class, 'saveCategory'])->name('admin.saveCategory');
        Route::post('delete-category', [CategoryController::class, 'deleteCategory'])->name('admin.deleteCategory');
    });


    Route::group(['prefix' => 'instructor', 'middleware' => ['role:instructor']], function () {
        Route::get('course-metrics', [InstructorController::class, 'metrics']);
        Route::get('profile', [InstructorController::class, 'profile']);
        Route::post('profile-save', [InstructorController::class, 'profileSave']);
        Route::get('course-delete/{course_id}', [InstructorController::class, 'instructorCourseDelete']);
        Route::get('course-list', [InstructorController::class, 'instructorCourseList']);
        Route::get('course-info', [InstructorController::class, 'instructorCourseInfo']);
        Route::get('course-info/{course_id}', [InstructorController::class, 'instructorCourseInfo']);
        Route::get('course-curriculum/{course_id}', [InstructorController::class, 'courseCurriculum']);





        Route::group(['middleware' => ['owner']], function () {
            Route::post('course-info-save', [InstructorController::class, 'instructorCourseInfoSave'])->name('instructor.course-info-save');
            Route::post('course-image-save', [InstructorController::class, 'courseImageSave']);

            Route::post('courses/section/save', [SectionController::class, 'save']);
            Route::post('courses/section/delete', [SectionController::class, 'delete']);
            Route::post('courses/lecture/save', [LectureController::class, 'save']);
            Route::post('courses/lecture/delete', [LectureController::class, 'delete']);

            Route::post('courses/lecture-content/save', [LectureContentController::class, 'save']);
        });
    });


    Route::group(['middleware' => ['role:student']], function () {
        Route::get('/is-enroll/{id}', [StudentController::class, 'isEnroll']);
        Route::post('/free-enroll', [StudentController::class, 'freeEnroll']);
    });
    Route::group(['prefix' => 'student', 'middleware' => ['role:student']], function () {

        Route::post('become-instructor', [StudentController::class, 'becomeInstructor'])->name('become-instructor');
        Route::get('my-courses', [StudentController::class, 'myCourses']);
    });



    Route::group(['middleware' => ['subscribed']], function () {
        Route::get('update-lecture-status/{course_id}/{lecture_id}/{status}', [CourseController::class, 'updateLectureStatus']);

        Route::get('course-enroll/{course_id}', [CourseController::class, 'courseEnroll']);
        Route::get('course-enroll-lecture/{course_id}/{lecture_id}', [CourseController::class, 'courseEnrollLecture']);
    });
});
