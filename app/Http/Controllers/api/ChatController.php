<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * get my chats
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chats = Chat::with(['sender', 'reciever'])
            ->with('messages', function ($query) {
                $query->where('sender_id', '!=', Auth::user()->id)
                    ->take(1);
            })->where('sender_id', Auth::user()->id)
            ->orWhere('reciever_id', Auth::user()->id)
            ->get();
        return response()->json(['data' => $chats], 200);
    }
    /**
     * get chat messages
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chat = Chat::where('sender_id', Auth::user()->id)
            ->orWhere('reciever_id', Auth::user()->id)
            ->findOrFail($id);
        $messages = Message::with(['sender'])
            ->where('chat_id', $chat->id)->get();
        return response()->json(['data' => $messages], 200);
    }
    /**
     * create new chat with a user
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function store(User $user)
    {
        $chat = Chat::create([
            'sender_id' => Auth::user()->id,
            'reciever_id' => $user->id
        ]);
        return response()->json(['data' => $chat], 200);
    }
}
