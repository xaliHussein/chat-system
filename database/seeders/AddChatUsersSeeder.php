<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Conversation;
use App\Models\ConversationsUsers;
use App\Models\ChatMessages;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddChatUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $Conversation =Conversation::create([

        // ]);
        // $Conversation =ChatMessages::create([
        //     "conversation_id" => "2",
        //     "sender_id" => "1",
        //     "receiver_id" => "5",
        //     "message" => "مرحبا",
        // ]);
        $Conversation =Conversation::create([
            "sender_id" => "1",
            "receiver_id" => "3",
        ]);
        $Conversation =Conversation::create([
            "sender_id" => "5",
            "receiver_id" => "1",
        ]);
    }
}
