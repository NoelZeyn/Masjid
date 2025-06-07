<?php

use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/submit-quiz', [QuizController::class, 'store']);
Route::get('/leaderboard', [QuizController::class, 'leaderboard']);

