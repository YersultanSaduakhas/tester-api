<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('register',[\App\Http\Controllers\AuthController::class,'register']);
Route::post('login',[\App\Http\Controllers\AuthController::class,'login']);
Route::get('unauth',[\App\Http\Controllers\AuthController::class,'unauth']);

Route::get('open/data/lessons', [\App\Http\Controllers\LessonController::class,'index']);
Route::get('open/data/questions/{id}', [\App\Http\Controllers\QuestionController::class,'show']);
Route::get('open/data/quiz_questions', [\App\Http\Controllers\QuizController::class,'createRandomQuizQuestion']);

Route::middleware('auth:sanctum')->group(function (){
    Route::get('user',[\App\Http\Controllers\AuthController::class,'user']);
    Route::post('logout',[\App\Http\Controllers\AuthController::class,'logout']);

    //todo admin middleware
    Route::post('/import_excel/import', [\App\Http\Controllers\ImportExcelController::class,'import']);
    Route::resource('lesson', '\App\Http\Controllers\LessonController', ['except' => 'getByLang']);
    Route::resource('question', '\App\Http\Controllers\QuestionController');
});