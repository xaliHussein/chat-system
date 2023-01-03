<?php

namespace App\Http\Controllers;

use App\Models\chat;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\SendResponse;
use App\Traits\UploadImage;
use App\Traits\Pagination;
use App\Events\ChatsSocket;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;


class ChatController extends Controller
{
    use SendResponse,UploadImage ,Pagination;

    public function getMessages(Request $request){
        $request = $request->json()->all();
        $validator = Validator::make($request,[
            'chat_id'=>'required',
        ]);
        if($validator->fails()){
            return $this->send_response(400,'فشل عملية',$validator->errors(),[]);
        }
        $chat = chat::where('from_user','=',$request['chat_id'])
        ->orWhere('to_user','=',$request['chat_id']);
        if(isset($_GET)){
            foreach($_GET as $key => $value){
                if($key == 'skip' || $key=='limit' || $key=='query' || $key=='filter'){
                }
            }
        }
         if (!isset($_GET['skip']))
            $_GET['skip'] = 0;
        if (!isset($_GET['limit']))
            $_GET['limit'] = 10;
        $res = $this->paging($chat->orderBy("created_at", "asc"),  $_GET['skip'],  $_GET['limit']);
        return $this->send_response(200,'تم المحادثات ',[],  $res["model"], null, $res["count"]);
    }
    public function sendMessage(Request $request){
        $request = $request->json()->all();
        $validator = Validator::make($request,[
            'message'=>'required',
            'from_user'=>'required|exists:users,id',
            'to_user'=>'required|exists:users,id',
        ]);
        if($validator->fails()){
            return $this->send_response(400,'فشل عملية',$validator->errors(),[]);
        }
        $message= chat::create([
            'message'=>$request['message'],
            'from_user'=>auth()->user()->id,
            'to_user'=>$request['to_user'],
            'seen'=>0
        ]);
         broadcast(new ChatsSocket($message,auth()->user()->id));
         broadcast(new ChatsSocket($message,$request['to_user']));
         return $this->send_response(200,'نجحت عملية',[],chat::find($message->id));
    }
}
