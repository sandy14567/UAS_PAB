<?php

use App\Models\membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\cardController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\membershipController;

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
Route::middleware(['auth:sanctum', 'kasir'])->post(
    '/user/register_kasir',
    [UserController::class, 'register_kasir']
);
Route::middleware(['auth:sanctum', 'kasir'])->post(
    '/user/register_membership',
    [UserController::class, 'register_membership']
);
Route::middleware(['auth:sanctum', 'kasir'])->post(
    '/logout',
    [UserController::class, 'logout']
);
Route::post('/login', [UserController::class, 'login']);
Route::middleware(['auth:sanctum', 'kasir'])->post(
    '/user/token',
    [UserController::class, 'membership_token']
);
Route::middleware(['auth:sanctum', 'kasir'])->get(
    '/user/list',
    [UserController::class, 'list']
);
Route::middleware(['auth:sanctum', 'kasir'])->post(
    '/membership/create',
    [membershipController::class, 'create']
);
Route::middleware(['auth:sanctum', 'kasir'])->get(
    '/membership/list',
    [membershipController::class, 'list']
);
Route::middleware(['auth:sanctum', 'kasir'])->post(
    '/card/create',
    [cardController::class, 'create']
);
Route::middleware(['auth:sanctum', 'kasir'])->get(
    '/card/list',
    [cardController::class, 'list']
);
Route::middleware(['auth:sanctum', 'membership'])->get(
    '/card/balance/{id}',
    [CardController::class, 'balance']
);
Route::middleware(['auth:sanctum', 'membership'])->post(
    '/card/belanja',
    [CardController::class, 'belanja']
);
Route::middleware(['auth:sanctum', 'membership'])->post(
    '/card/topup',
    [CardController::class, 'topup']
);
