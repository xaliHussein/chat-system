<?php

namespace App\Http\Controllers;

use App\Models\chat;
use App\Models\User;
use App\Models\ChatUser;
use App\Traits\Pagination;
use App\Traits\UploadImage;
use App\Models\Conversation;
use App\Traits\SendResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class ConversationsController extends Controller
{
    use SendResponse,UploadImage ,Pagination;
     public function getConversations(){
      $conversation=Conversation::where('sender_id',auth()->user()->id)
      ->orWhere('receiver_id',auth()->user()->id);

        // if (isset($_GET['filter'])) {
        //     $filter = json_decode($_GET['filter']);
        //     // return $filter;
        //     $resturant->where($filter->name, $filter->value);
        // }

        // if (isset($_GET['query'])) {
        //     $columns = Schema::getColumnListing('resturants');
        //     $resturant->whereHas('user', function ($query) {
        //         $query->where('name', 'like', '%' . $_GET['query'] . '%');
        //     });
        //     foreach ($columns as $column) {
        //         $resturant->orWhere($column, 'LIKE', '%' . $_GET['query'] . '%');
        //         error_log($column);
        //     }
        // }
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
        $res = $this->paging($conversation->orderBy("created_at", "desc"),  $_GET['skip'],  $_GET['limit']);
        return $this->send_response(200,'تم جلب المحادثه',[],  $res["model"], null, $res["count"]);
    }
}
