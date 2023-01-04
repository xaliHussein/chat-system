<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * send message to a chat
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $request->validate(['content' => 'required|max:255']);
        $chat = Chat::where('sender_id', Auth::user()->id)
            ->orWhere('reciever_id', Auth::user()->id)
            ->findOrFail($id);
        $message = Message::create([
            'sender_id' => Auth::user()->id,
            'content' => $request->content,
            'chat_id' => $chat->id
        ]);
        if ($message)
            return response()->json(['data' => $message], 200);
        return response()->json(['message' => 'server error'], 500);
    }
    /**
     * set message as delivered or seen
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:delivered,seen']);
        $message = Message::whereHas('chat', function ($query) {
            $query->where('reciever_id', Auth::user()->id);
        })->findOrFail($id);
        $message->update(['statue' => $request['status']]);
        return response()->json(['data' => $message], 200);
    }
    /**
     * delete sent message
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Message::whereHas('chat', function ($query) {
            $query->where('reciever_id', Auth::user()->id);
        })->destroy($id);
        return response()->json(['message' => 'success'], 200);
    }
}
