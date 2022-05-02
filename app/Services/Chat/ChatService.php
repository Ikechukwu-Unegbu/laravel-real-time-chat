<?php
namespace App\Services\Chat;

use App\Models\Chat;
use Illuminate\Support\Facades\Auth;

class ChatService{
  public function __construct()
  {
    
  }

  public function getChat($chatId){
    $attachment = null;
    $attachment_title = null;

    $message = Chat::where('id', $chatId)->first();

    //check if message have attachment
    if(isset($message->attachment)){
      $attachmentOBJ = json_decode($message->attachment);
      $attachment = $attachmentOBJ->new_name;
      $attachment_title = htmlentities(trim($attachmentOBJ->old_name), ENT_QUOTES, 'UTF-8');

      $ext = pathinfo($attachment, PATHINFO_EXTENSION);
    }
    return [
      'id'=>$message->id,
      'from_id'=>$message->from_id,
      'to_id'=>$message->to_id,
      'chat'=>$message->chat, 
      'attachment'=>[$attachment, $attachment_title],
      'time'=>$message->created_at->diffForHumans(),
      'fullTime'=>$message->created_at,
      'viewType' => ($message->from_id == Auth::user()->id) ? 'sender' : 'default',
      'seen'=>$message->seen,
      'notice'=>$message->notice,
    ];
  }


  public function newMessage($data){
    $chat = new Chat();
    $chat->id = $data['id'];
    $chat->from_id = $data['from_id']; 
    $chat->to_id = $data['to_id'];
    $chat->chat = $data['chat'];
    $chat->attachment = $data['attachment'];
    $chat->save();
  }

  public function markSeen($user_id){
    Chat::where('from_id', $user_id)
          ->wher('to_id', Auth::user()->id)
          ->where('seen',0)->update(['seen'=>1]);
          return 1;
  }

  public function countUseenMessages($user_id){
    return Chat::where('from_id',$user_id)->where('to_id',Auth::user()->id)->where('seen',0)->count();
  }

  // fetch messages between a user and sender
  public function getMessagesQuery($user_id){
    return Chat::where('from_id', Auth::user()->id)->where('to_id', $user_id)
            ->orwhere('from_id', $user_id)->where('to_id', Auth::user()->id);
  }

  public function allowedImages(){
    return config('chat.attachments.allowed_images');
  }

  public function allowedFiles(){
    return config('chat.attachments.allowed_files');
  }

  public function allowedFileSize(){
    return config('chat.attachments.max_upload_size');
  }
}