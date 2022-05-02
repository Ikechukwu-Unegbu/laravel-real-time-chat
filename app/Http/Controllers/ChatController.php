<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\Chat;
use App\Models\User;
use App\Services\Chat\ChatService;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return response()->json($request->all());die;
        $error =  (object)[
            'status'=>0,
            'message'=>null
        ];
        $attachment = null;
        $attachment_title = null;
        //check if there is a file or image and upload it to folder
        if($request->hasFile('file')){
            $allowed_images = $this->chatService->allowedImages();
            $allowed_files = $this->chatService->allowedFiles();
            $allowed = array_merge($allowed_images, $allowed_files);
            $file = $request->file('file');
            if($file->getSize() <$this->chatService->allowedFileSize()){
                if(in_array($file->getClientOriginalExtension(), $allowed)){
                    //upload 
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
        //insert full message to database along with attachment folder path
        if($error->status){
            event(new NewMessage($error));
        }else{
            $chatService = new ChatService();
            $chatId = mt_rand(9, 999999999) + time();
            $chatService->newMessage([
            'id'=>$chatId,
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
    public function get(Request $request){
        $query = $this->chatService->getMessagesQuery($request->id)->latest();
        $chats = $query->paginate($request->per_age??$this->perPage);
        $totalChats = $chats->total();
        $lastPage = $chats->lastPage();
        $response = [
            'total'=>$totalChats,
            'last_page'=>$lastPage,
            'last_message_id'=>collect($chats->items())->last()->id??null,
            'messages'=>'',
        ];
        //if there is no messages yet
        if($totalChats<1){
            $response['messages'] ='<p class="message-hint center-el"><span>Say \'hi\' and start messaging</span></p>';
            return FacadesResponse::json($response);
        }

       $chatsReverse = $chats->reverse();
       $response['messages'] = $chatsReverse;
       return FacadesResponse::json($response);
        
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
}
