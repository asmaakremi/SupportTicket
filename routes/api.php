<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketRepliesController;
use App\Models\Ticket;

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

Route::post('/tickets/{id}/replies', [TicketRepliesController::class, 'addReply']);
Route::put('/tickets/{id}/status', [TicketController::class, 'updateStatus']);
Route::resource('/tickets', TicketController::class);
Route::post('/signup', [UserController::class, 'signUp']);
Route::post('/signin', [UserController::class, 'signIn']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
