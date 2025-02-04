<?php

use App\Http\Middleware\UserIdentity;
use App\Student;
use App\SystemAdmin;
use App\Teacher;

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

// 通用约定
Route::patterns([
    'schoolId' => '[0-9]+',
    'teacherId' => '[0-9]+',
    'studentId' => '[0-9]+',
]);


// Auth
Route::post('auth/login', 'Api\AuthController@login');
Route::post('auth/register', 'Api\AuthController@register');
Route::post('auth/line/login', 'Api\LineController@login');
Route::post('auth/line/users', 'Api\LineController@users');


Route::middleware('auth:api')->group(function () {
    Route::post('line/bind', 'Api\LineController@bind');
    Route::put('line/unbind', 'Api\LineController@unbind');

    // Role: 学生
    Route::middleware(UserIdentity::class . ':' . Student::class)->group(function () {
        Route::get('students/following', 'Api\StudentController@listFollowing');
        Route::post('students/follow/{teacherId}', 'Api\StudentController@follow');
        Route::delete('students/unfollow/{teacherId}', 'Api\StudentController@unfollow');
        Route::get('students/school-info', 'Api\StudentController@schoolInfo');
        Route::get('students/school-teachers', 'Api\StudentController@schoolTeachers');
        Route::post('messages/student-talk/{teacherId}', 'Api\MessageController@studentTalk');
    });

    // Role: 教师
    Route::middleware(UserIdentity::class . ':' . Teacher::class . ',' . SystemAdmin::class)->group(function () {
        // 学校
        Route::post('schools/create', 'Api\SchoolController@create');
        Route::get('schools', 'Api\SchoolController@list');
        // 学校管理员
        Route::post('schools/students/{schoolId}/create', 'Api\SchoolController@createStudent');
        Route::post('schools/teachers/{schoolId}/invite', 'Api\SchoolController@inviteTeacher');
        Route::post('schools/messages/{schoolId}/send/{studentId}', 'Api\SchoolController@sendMessage');
        // 学生
        Route::get('teachers/students/teaching', 'Api\TeacherController@listTeaching');
        Route::get('teachers/students/following', 'Api\TeacherController@listFollowing');

        Route::post('messages/teacher-talk/{studentId}', 'Api\MessageController@teacherTalk');
    });

    // Role: 系统管理员
    Route::middleware(UserIdentity::class . ':' . SystemAdmin::class)->group(function () {

        Route::put('schools/approve/{schoolId}/{action}', 'Api\SchoolController@approve');

    });

});
