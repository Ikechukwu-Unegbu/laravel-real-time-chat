<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(){
        $user = User::find(Auth::user()->id);
        return view('chat.index')->with('user', $user);
    }

    public function send(Request $request){
        //check if there is a file or image and upload it to folder

        //insert full message to database along with attachment folder path
        $chat = new Chat();
        

        //if that is successfull, send message paylaod to the reciever view event broadcast
        

        //if not succesful, return to the very same page error message
    }

    public function getChat(){
        
    }
}
