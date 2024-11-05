<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegistrationController;
use App\Http\Controllers\Api\V1\Auth\VerificationController;
use App\Http\Controllers\API\V1\Category\CategoryController;

Route::prefix('/auth')
->middleware(['api', 'jwt.auth'])
->group(function () {
    Route::post('/register', [RegistrationController::class, 'registerWithEmail'])->withoutMiddleware('jwt.auth');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->name('verification.verify')->withoutMiddleware('api');

    Route::post('/email/resend', [VerificationController::class, 'resend'])
    ->name('verification.resend')->withoutMiddleware('api');

    Route::post('/login', [LoginController::class, 'loginWithEmail'])->withoutMiddleware('api');
    // Route::post('/logout', [AuthController::class, 'logout']);
});
// Category
Route::get('/categories', [CategoryController::class, 'listCategory']);

