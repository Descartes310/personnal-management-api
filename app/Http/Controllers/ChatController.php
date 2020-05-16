<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ChatMessage;
use App\ChatDiscussion;
use App\APIError;
use App\User;
use App\UserProfile;
use Carbon\Carbon;
use Auth;

class ChatController extends Controller
{
    
    public function getDiscussions($id)
    {
        $chats = ChatDiscussion::whereUser1IdOrUser2Id($id, $id)->orderBy('updated_at', 'desc')->get();
        $data = [];

        foreach ($chats as $chat) {
            if($chat->user1_id != $chat->user2_id) {
                $unread = ChatMessage::whereDiscussionId($chat->id)->where('sender_id', '<>', $id)->whereNull('viewed_at')->count();
                $user = null;
                if($chat->user1_id != $id) {
                    $user = User::whereId($chat->user1_id)->first();
                    $user_infos = UserProfile::whereUserId($user->id)->with('profile')->get();
                    foreach ($user_infos as $user_info) {
                        if($user_info->profile->type == 'file')
                            $user[$user_info->profile->slug] = url($user_info->value);
                        else
                            $user[$user_info->profile->slug] = $user_info->value;
                    }
                } else {
                    $user = User::whereId($chat->user2_id)->first();
                    $user_infos = UserProfile::whereUserId($user->id)->with('profile')->get();
                    foreach ($user_infos as $user_info) {
                        if($user_info->profile->type == 'file')
                            $user[$user_info->profile->slug] = url($user_info->value);
                        else
                            $user[$user_info->profile->slug] = $user_info->value;
                    }
                }
                $chat['unread'] = $unread;
                $chat['user'] = $user;
                array_push($data, $chat);
            }
        }

        return response()->json($data, 200);
    }

    public function getNewMessages($id)
    {
        $chat = ChatDiscussion::find($id);
        $user = Auth::user();
        if(!$chat) {
            if(!$chat) {
                $unauthorized = new APIError;
                $unauthorized->setStatus("404");
                $unauthorized->setCode("DISCUSSION_NOT_FOUND");
                $unauthorized->setMessage("Discussion non existante.");
                return response()->json($unauthorized, 404);
            }
        }
        $messages = $chat->messages()->whereNull('viewed_at')->where('sender_id', '<>', $user->id)->get();
        foreach ($messages as $message) {
            if($user->id != $message->sender_id) {
                $message->update([
                    'viewed_at' => Carbon::now()
                ]);
            }
        }
        return response()->json($messages, 200);
    }

    public function deleteDiscussion($id) {
        $chat = ChatDiscussion::find($id);
        if($chat == null) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("DISCUSSION_NOT_FOUND");
            $apiError->setMessage("no discussion found with id $id");
            return response()->json($apiError, 404);
        }
        $chat->delete();
        return response()->json(200);
    }

    public function deleteMessage($id) {
        $message = ChatMessage::find($id);
        $user = Auth::user();
        if($message == null) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("MESSAGE_NOT_FOUND");
            $apiError->setMessage("no message found with id $id");
            return response()->json($apiError, 404);
        }
        if($message->sender_id == $user->id) {
            $message->update([
                "sender_delete_at" => Carbon::now()
            ]);
        } else {
            $message->update([
                "receiver_delete_at" => Carbon::now()
            ]);
        }
        return response()->json(200);
    }

    public function newMessage(Request $request)
    {
        $data = $request->except('file');
        $message = [];
        $chat = null;

        $this->validate($data, [
            'sender_id' => 'exists:users,id',
            'receiver_id' => 'exists:users,id',
        ]);
        if(isset($request->discussion_id)) {
            $chat = ChatDiscussion::find($request->discussion_id);
            if($chat == null) {
                $apiError = new APIError;
                $apiError->setStatus("404");
                $apiError->setCode("DISCUSSION_NOT_FOUND");
                $apiError->setMessage("no discussion found with id $id");
                return response()->json($apiError, 404);
            }
            
        } else {
            $chat['user1_id'] = $request->sender_id;
            $chat['user2_id'] = $request->receiver_id;
            $chat['last_message'] = "";
            $chat = ChatDiscussion::create($chat);
        }

        $message['sender_id'] =$request->sender_id;
        $message['file'] = null;
        $message['viewed_at'] = null;
        $message['content'] = $request->message;
        $chat['last_message'] = $request->message;
        $chat['file'] = null;
        $message['discussion_id'] = $chat->id;

        if ($file = $request->file('file')) {
            $this->validate($data, ['file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20048']);
            $extension = $file->getClientOriginalExtension();
            $relativeDestination = "uploads/messages";
            $destinationPath = public_path($relativeDestination);
            $safeName = str_replace(' ', '_', 'private_chat') . time() . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $message['file'] = "$relativeDestination/$safeName";
            $chat['file'] = "$relativeDestination/$safeName";
        }        

        $message = ChatMessage::create($message);
        $chat->update([
            'file' => $chat['file'],
            'last_message' => $data['message']
        ]);
        $message['discussion_id'] = $chat->id;

        return response()->json($message, 200);
    }

    public function discussionMessage($id) {
        $discussion = ChatDiscussion::find($id);
        $user = Auth::user();
        if($discussion == null) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("DISCUSSION_NOT_FOUND");
            $apiError->setMessage("no discussion found with id $id");
            return response()->json($apiError, 404);
        }
        $discussion->messages;
        foreach ($discussion->messages as $message) {
            if($user->id != $message->sender_id && $message->viewed_at == null) {
                $message->update([
                    'viewed_at' => Carbon::now()
                ]);
            }
            if($message->sender_delete_at != null) {
                $message['sender_delete'] = true;
            }
            if($message->receier_delete_at != null) {
                $message['receiver_delete'] = true;
            }
        }
        return response()->json($discussion, 200);
    }

}
