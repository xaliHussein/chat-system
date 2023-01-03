<?php

use App\Events\Hello;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\ConversationsController;

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
Broadcast::routes(['middleware' => ['auth:api']]);
route::post('login',[UserController::class,'login']);
route::post('add_user',[UserController::class,'addUser']);

Route::middleware(['auth:api'])->group(function () {
    route::post('change_user_online',[UserController::class,'ChangeUserOnline']);
    route::post('change_user_offline',[UserController::class,'ChangeUserOffline']);
    route::get('get_info_user',[UserController::class,'getInfoUser']);
    route::get('initials',[UserController::class,'initials']);

    route::post('get_messages',[ChatController::class,'getMessages']);
    route::post('send_message',[ChatController::class,'sendMessage']);
    route::get('get_conversations',[ConversationsController::class,'getConversations']);
});

