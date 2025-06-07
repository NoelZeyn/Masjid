<?php
// routes/api.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;

Route::post('/submit-quiz', [QuizController::class, 'store']);
Route::get('/leaderboard', [QuizController::class, 'leaderboard']);
