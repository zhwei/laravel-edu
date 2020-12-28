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


Route::middleware('auth:api')->group(function () {

    // Role: 学生
    Route::middleware(UserIdentity::class . ':' . Student::class)->group(function () {
        Route::get('students/following', 'Api\StudentController@listFollowing');
        Route::post('students/follow/{teacherId}', 'Api\StudentController@follow');
        Route::delete('students/unfollow/{teacherId}', 'Api\StudentController@unfollow');
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
        Route::get('teachers/students/teaching', 'Api\StudentController@listTeaching');
        Route::get('teachers/students/following', 'Api\StudentController@listFollowing');
    });

    // Role: 系统管理员
    Route::middleware(UserIdentity::class . ':' . SystemAdmin::class)->group(function () {

        Route::put('schools/approve/{schoolId}/{action}', 'Api\SchoolController@approve');

    });

});
