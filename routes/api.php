<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SecurityController;
use App\Http\Controllers\Api\StatController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => '/users'], function () {
    Route::get('', [UserController::class, 'getUsers']);
    Route::get('/{userId}', [UserController::class, 'getById']);
    Route::get('/{userId}/posts', [UserController::class, 'getUserPosts']);
    Route::get('/{userId}/activity', [UserController::class, 'getUserActivity']);
});

Route::group(['prefix' => '/posts'], function () {
    Route::get('', [PostController::class, 'getPosts']);
    Route::get('/{postId}/comments', [PostController::class, 'getPostComments']);
});

Route::get('/comments', [CommentController::class, 'getComments']);

Route::group(['prefix' => '/reports'], function () {
    Route::get('', [ReportController::class, 'getReports']);
    Route::get('/summary', [ReportController::class, 'getReportsSummary']);
});

Route::group(['prefix' => '/security'], function () {
    Route::get('/logs', [SecurityController::class, 'getSecurityLogs']);
    Route::get('/blocked-ips', [SecurityController::class, 'getBlockedIps']);
    Route::get('/blocked-phones', [SecurityController::class, 'getBlockedPhones']);
    Route::get('/settings', [SecurityController::class, 'getSecuritySettings']);
});

Route::group(['prefix' => '/stats'], function () {
    Route::get('/overview', [StatController::class, 'getOverView']);
});
