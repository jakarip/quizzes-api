<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\QuizController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\AnswerController;

Route::apiResource('/quizzes', QuizController::class);
Route::apiResource('/questions', QuestionController::class);
Route::apiResource('/answers', AnswerController::class);