<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\LearningController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return response()->json([
        'code' => 401,
        'status' => false,
        'message' => 'akses tidak diperbolehkan',
    ],401); 
})->name('login');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// Class
Route::get('/class', [GradeController::class, 'getGrade'])->middleware('auth:sanctum');
Route::post('/class', [GradeController::class, 'addGrade'])->middleware('auth:sanctum');
Route::get('/class/{id}', [GradeController::class, 'findGrade'])->middleware('auth:sanctum');
Route::put('/class/{id}', [GradeController::class, 'updateGrade'])->middleware('auth:sanctum');
Route::delete('/class/{id}', [GradeController::class, 'deleteGrade'])->middleware('auth:sanctum');


// User
Route::get('/user', [UserController::class, 'getUser']);
Route::post('/user', [UserController::class, 'addUser']);
Route::get('/user/{id}', [UserController::class, 'findUser']);
Route::post('/user/{id}', [UserController::class, 'updateUser']);
Route::delete('/user/{id}', [UserController::class, 'deleteUser']);

// Materi
Route::get('/materi', [LearningController::class, 'getLearning']);
Route::post('/materi', [LearningController::class, 'addLearning']);
Route::get('/materi/{id}', [LearningController::class, 'findLearning']);
Route::put('/materi/{id}', [LearningController::class, 'updateLearning']);
Route::delete('/materi/{id}', [LearningController::class, 'deleteLearning']);


// Tugas
Route::get('/tugas', [TaskController::class, 'getTask']);
Route::post('/tugas', [TaskController::class, 'addTask']);
Route::get('/tugas/{id}', [TaskController::class, 'findTask']);
Route::put('/tugas/{id}', [TaskController::class, 'updateTask']);
Route::delete('/tugas/{id}', [TaskController::class, 'deleteTask']);