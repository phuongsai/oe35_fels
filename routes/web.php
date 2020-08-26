<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [
    'as' => 'homepage',
    'uses' => 'Fels\CourseController@getPopularCourses',
]);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Admin Management

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {
    Route::resource('courses', 'CourseController');

    // get course list for lesson
    Route::get('/courses-list', 'CourseController@getCourseList')->name('courses.list');

    Route::resource('lessons', 'LessonController');
});

// E-learning

Route::group(['namespace' => 'Fels'], function () {
    // Courses
    Route::group(['as' => 'fels.course.'], function () {
        Route::get('/courses', [
            'uses' => 'CourseController@getAllCourses',
            'as' => 'list',
        ]);

        Route::group(['middleware' => ['auth']], function () {
            Route::get('/course/{course}', [
                'uses' => 'CourseController@getCourseInfo',
                'as' => 'detail',
            ]);

            Route::post('/remember-word/{wordId}', [
                'uses' => 'CourseController@rememberWord',
                'as' => 'remember',
            ]);
        });
    });
});
