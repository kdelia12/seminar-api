<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\UserController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');
Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');
//gwt thw uawe controllwe
// Route::get('/user', 'App\Http\Controllers\Api\UserController@show')->name('user');
Route::post('/user', App\Http\Controllers\Api\UserController::class)->name('user');
// Route::get('/seminars', App\Http\Controllers\Api\SeminarsController::class)->name('index');]\

//Seminar api
use App\Http\Controllers\Api\SeminarsController;
Route::get('/seminars/upcoming', [SeminarsController::class, 'upcoming_seminar']);
Route::get('/seminars/past', [SeminarsController::class, 'past_seminar']);
Route::post('/seminars', 'App\Http\Controllers\Api\SeminarsController@store');
Route::delete('/seminars/{seminar}', 'App\Http\Controllers\Api\SeminarsController@destroy');
Route::post('/seminars/{seminar}/apply', 'App\Http\Controllers\Api\SeminarsController@apply');
Route::get('/seminars/applied', 'App\Http\Controllers\Api\SeminarsController@get_all_seminar_applied');
Route::get('/seminars/{seminar}', 'App\Http\Controllers\Api\SeminarsController@get_all_seminar_applicant');
Route::get('/seminars/{seminar}/check', 'App\Http\Controllers\Api\SeminarsController@check_apply');
Route::get('/seminars/details/{seminar}', 'App\Http\Controllers\Api\SeminarsController@getseminardata');
Route::post('/ratings/add', 'App\Http\Controllers\Api\RatingsController@addratings');
Route::get('/seminars/{id_seminar}/stars', 'App\Http\Controllers\Api\RatingsController@getseminarstars');
Route::get ('/user/{id_seminar}/stars', 'App\Http\Controllers\Api\RatingsController@getuserstars');


