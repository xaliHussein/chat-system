<?php

namespace App\Http\Controllers;

use App\Models\chat;
use App\Models\User;
use App\Events\UserOnline;
use App\Traits\Pagination;
use App\Events\UserOffline;
use App\Traits\UploadImage;
use App\Traits\SendResponse;
use Illuminate\Http\Request;
use App\Events\UserStatusSocket;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use SendResponse,Pagination;
    public function rand_color() {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }

     public function login(Request $request){
        $request = $request->json()->all();
        $validator = Validator::make($request,[
            'user_name'=>'required',
            'password'=>'required'
        ],[
            'user_name.required'=>'اسم المستخدم مطلوب',
            'password.required'=>'كلمة المرور مطلوبة'
        ]);
        if($validator->fails()){
            return $this->send_response(400,'فشل عملية تسجيل الدخول',$validator->errors(),[]);
        }
        if(auth()->attempt(array('user_name'=> $request['user_name'], 'password'=> $request['password']))){
            $user=auth()->user();
                $token= $user->createToken('chat_system')->accessToken;
                // $user->update([
                //     'status' => 1,
                // ]);
                return $this->send_response(200,'تم تسجيل الدخول بنجاح',[], $user, $token);
        }else{
            return $this->send_response(400, 'هناك مشكلة تحقق من تطابق المدخلات', null, null, null);
        }
    }
    public function addUser(Request $request){
        $request= $request->json()->all();
        $validator= Validator::make($request,[
            'name'=>'required',
            'user_name'=>'required|unique:users,user_name',
            'phone_number'=>'required|unique:users,phone_number',
            'password'=>'required'
        ],[
            'name.required'=>'حقل الاسم مطلوب',
            'user_name.required'=>' اسم المستخدم مطلوب',
            'user_name.unique'=>'اسم المستحدم موجود مسبقا',
            'phone_number.required'=>'رقم الهاتف مطلوب',
            'phone_number.unique'=>'رقم الهاتف موجود مسبقا',
            'password.required'=>'كلمة المرور مطلوبة'
        ]);
        if($validator->fails()){
            return $this->send_response(400,'فشل عملية تسجيل الدخول',$validator->errors(),[]);
        }

        $user = User::create([
            'name'=> $request['name'],
            'user_name'=>$request['user_name'],
            'status'=>0,
            'color_avater'=>$this->rand_color(),
            'phone_number'=>$request['phone_number'],
            'password'=>bcrypt($request['password'])
        ]);
        return $this->send_response(200,'تم اضافة الحساب بنجاح',[], User::find($user->id));
    }
    public function ChangeUserOnline(Request $request){
        $request = $request->json()->all();
        $validator = Validator::make($request,[
            'user_id'=>'required',
        ]);
        $user = User::find($request['user_id']);
        $user->update([
            'status' => 1,
        ]);
        broadcast(new UserOnline(User::find($user->id)));
        return $this->send_response(200,'تم بنجاح',[],User::find($user->id));
    }
    public function ChangeUserOffline(Request $request){
        $request = $request->json()->all();
        $validator = Validator::make($request,[
            'user_id'=>'required',
        ]);
        $user = User::find($request['user_id']);
        $user->update([
            'status' => 0,
        ]);
        broadcast(new UserOffline($user));
        return $this->send_response(200,'تم بنجاح',[],User::find($user->id));
    }
    public function getInfoUser(){
        $user = User::find(auth()->user()->id);
        return $this->send_response(200,'تم جلب الحساب بنجاح',[], $user);
    }
    public function initials(){
        $user=User::where('id',auth()->user()->id)->with('conversations');
        if (!isset($_GET['skip']))
            $_GET['skip'] = 0;
        if (!isset($_GET['limit']))
            $_GET['limit'] = 10;
        $res = $this->paging($user->orderBy("created_at", "desc"),  $_GET['skip'],  $_GET['limit']);
        return $this->send_response(200,'تم جلب المحادثه',[],  $res["model"], null, $res["count"]);
    }
}
