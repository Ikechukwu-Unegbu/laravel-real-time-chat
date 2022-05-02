<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\Chat;
use App\Models\User;
use App\Services\Chat\ChatService;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Illuminate\Support\Str;


class ChatController extends Controller
{
    public $chatService;
    public function __construct(ChatService $chatService)
    {
        $this->chatService = new ChatService();
    }
 
    public function index(){
        $user = User::find(Auth::user()->id);
        return view('chat.index')->with('user', $user);
    }

    public function send(Request $request){
        //useful variables
        //return response()->json($request->all());die;
        $error =  (object)[
            'status'=>0,
            'message'=>null
        ];
        $attachment = null;
        $attachment_title = null;
        //check if there is a file or image and upload it to folder
        if($request->hasFile('file')){
            //var_dump('has file');die;
            $allowed_images = $this->chatService->allowedImages();
            $allowed_files = $this->chatService->allowedFiles();
            $allowed = array_merge($allowed_images, $allowed_files);
            $file = $request->file('file');
            if($file->getSize() >$this->chatService->allowedFileSize()){
                if(in_array($file->getClientOriginalExtension(), $allowed)){
                    //upload
                    //var_dump('image met all req');die; 
                    $attachment_title = $file->getClientOriginalName();
                    $attachment = Str::uuid(). ".".$file->getClientOriginalName();
                    $file->storeAs("public/chats", $attachment);
                }else{
                    $error->stats = 1;
                    $error->message = 'File extention not allowed';
                }
            }else{
                //what do when img size is too large
                $error->status = 1;
                $error->message = 'File size is too large.';
            }
            
        }
        $chatId = mt_rand(9, 999999999) + time();
        //insert full message to database along with attachment folder path
        if($error->status){
            event(new NewMessage($error));
        }else{
            $chatService = new ChatService();
            // $chatId = mt_rand(9, 999999999) + time();
            $chatService->newMessage([
            'uid'=>$chatId,
            'from_id' => Auth::user()->id,
            'to_id' => $request->to,
            'chat' => htmlentities(trim($request->message), ENT_QUOTES, 'UTF-8'),
            'attachment' =>  ($attachment) ? json_encode((object)[
                'new_name' => $attachment,
                'old_name' => htmlentities(trim($attachment_title), ENT_QUOTES, 'UTF-8'),
            ]) : null,
            ]);
            //fetch message to send 
            $chatData = $chatService->getChat($chatId);
            
            event(new NewMessage($chatData));
        }
    }

    //gets user messages from database
    public function getChatWithUser($userid){
        $query = $this->chatService->getMessagesQuery($userid)->latest();
        $chats = $query->paginate(30);
        $totalChats = $chats->count();
        return response()->json($chats);
        // $lastPage = $chats->lastPage();
        $response = [
            'total'=>$totalChats,
            // 'last_page'=>$lastPage,
            'last_message_id'=>collect($chats->items())->last()->id??null,
            'messages'=>'',
        ];
        //if there is no messages yet
        if($totalChats<1){
            $response['messages'] ='<p class="message-hint center-el"><span>Say \'hi\' and start messaging</span></p>';
            return response()->json($response);
        }

       $chatsReverse = $chats->reverse();
       $response['messages'] = $chatsReverse;
       return response()->json($response);
        
    }


    public function search(Request $request){
        $getRecords = null;
        $input = trim($request->input);
        //FILTER_SANITIZE_STRING
        $records = User::where('id', '!=', Auth::user()->id)
                    ->where('name', 'LIKE', "%".$request->input."%")
                    ->paginate(30);

        return response()->json([
             'records'=>$records,
             'total'=>$records->count()
        ]);
    }

    public function getContacts(){
        $users = Chat::join('users',  function ($join) {
            $join->on('chats.from_id', '=', 'users.id')
                ->orOn('chats.to_id', '=', 'users.id');
        })
        ->where(function ($q) {
            $q->where('chats.from_id', Auth::user()->id)
            ->orWhere('chats.to_id', Auth::user()->id);
        })
        ->where('users.id','!=',Auth::user()->id)
        ->select('users.*',DB::raw('MAX(chats.created_at) max_created_at'))
        ->orderBy('max_created_at', 'desc')
        ->groupBy('users.id')
        ->paginate(30);

        return response()->json($users);
    }
}
