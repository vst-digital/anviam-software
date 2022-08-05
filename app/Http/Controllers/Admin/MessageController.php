<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\UserMessage;
use App\Models\ChatGroup;
use App\Models\ChatGroupMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\PrivateMessageEvent;
use App\Events\GroupMessageEvent;
use File;

class MessageController extends Controller
{
    public function conversation($userId=null) {
        if($userId != null){
            $friendInfo = User::findOrFail($userId);
            $messages 	= UserMessage::with('message','reciever_users','sender_users')->where('sender_id',Auth::id())->where('receiver_id',$userId)->orWhere('receiver_id',Auth::id())->where('sender_id',$userId)->where('type',0)->get();
        }else{
            $friendInfo = collect([]);
            $messages   = collect([]);
        }


        $chatgroup 	= ChatGroupMember::with('chatgroup')->where('group_member_id', Auth::id())->get();

        $companyid = Auth::user()->company_id;
        $users = User::where('id', '!=', Auth::id())->where('company_id',$companyid)->get();

        $myInfo 	= User::find(Auth::id());

        //echo '<pre>';print_r($messages->toArray());die;
        $this->data['users']        = $users;
        $this->data['friendInfo']   = $friendInfo;
        $this->data['myInfo']       = $myInfo;
        $this->data['users']        = $users;
        $this->data['messages']     = $messages;
        $this->data['chatgroup']    = $chatgroup;
        $this->data['chattype']     = 'user';

        return view('admin.message.conversation', $this->data);
    } 

    public function chat(){
        $chatgroup 	= ChatGroupMember::with('chatgroup')->where('group_member_id', Auth::id())->get();
        $companyid = Auth::user()->company_id;
        $users = User::where('id', '!=', Auth::id())->where('company_id',$companyid)->get();
        $friendInfo = User::findOrFail(Auth::id());
        $data['users'] = $users;
        $data['chatgroup'] = $chatgroup;
        $data['friendInfo']   = $friendInfo;
        $data['chattype']     = 'user';

        return view('admin.chat', $data);
    }
    public function sendMessage(Request $request) {
        $request->validate([
           'receiver_id' => 'required'
        ]);
        //echo '<pre>';print_R($_FILES);die;
        $sender_id = Auth::id();
        $receiver_id = $request->receiver_id;
        $image = '';
        $message = new Message();
        $message->message = $request->message;
        if ($request->hasfile('file')) {
            $file = $request->file('file');
            if ($file) {
                $path = public_path("uploads/chatImages");
                if (!File::isDirectory($path)) {
                    File::makeDirectory($path, 0777, true, true);
                }
                if($request->ext != ''){
                    $extension = $request->ext;
                }else{
                    $extension = $file->getClientOriginalExtension(); // getting image extension
                }

                $media_filename = uniqid(time()) .'.'. $extension;
                $file->move($path, $media_filename);
                $image = $media_filename;
                $message->message   = $image;
                $message->type      = 2;
            }
        }//echo $message->message.'<pre>';print_r($message);die;
        if ($message->save()) {
            try {
                $message->users()->attach($sender_id, ['receiver_id' => $receiver_id]);
                $sender = User::where('id', '=', $sender_id)->first();
               $ext = pathinfo($message->message);
                $data = [];
                $data['sender_id']      = $sender_id;
                $data['sender_name']    = $sender->first_name.' '.$sender->last_name;
                $data['receiver_id']    = $receiver_id;
                if($message->type == 2){
                    if($ext['extension'] == 'wav'){
                        $data['content']    = "<audio controls='' src='".$message->message."'></audio>";
                    }else{
                        $data['content']    = "<img src='".$message->message."' style='width:250px;'/>";
                    }

                    $data['image']      = $image;
                    $data['file']    = 1;
                }else{
                    $data['content'] = $message->message;
                    $data['file']    = 0;
                    $data['image']   = $image;
                }

                $data['created_at']     = $message->created_at;
                $data['message_id']     = $message->id;

                event(new PrivateMessageEvent($data));

                return response()->json([
                   'data' => $data,
                   'success' => true,
                    'message' => 'Message sent successfully'
                ]);
            } catch (\Exception $e) {
                $message->delete();
            }
        }
    }


    public function sendGroupMessage(Request $request) {
        $request->validate([
           'group_id' => 'required'
        ]);
        //echo '<pre>';print_R($_FILES);die;
        $sender_id  = Auth::id();
        $group_id   = $request->group_id;
        $image = '';
        $message = new Message();
        $message->message = $request->message;
        //$message->group_id = $request->group_id;
        if ($request->hasfile('file')) {
            $file = $request->file('file');
            if ($file) {
                $path = public_path("uploads/chatImages");
                if (!File::isDirectory($path)) {
                    File::makeDirectory($path, 0777, true, true);
                }
                // $extension = $file->getClientOriginalExtension(); // getting image extension
                if($request->ext != ''){
                    $extension = $request->ext;
                }else{
                    $extension = $file->getClientOriginalExtension(); // getting image extension
                }
                $media_filename = uniqid(time()) . '.' . $extension;
                $file->move($path, $media_filename);
                $image = $media_filename;
                $message->message   = $image;
                $message->type      = 2;
            }
        }//echo $message->message.'<pre>';print_r($message);die;
        if ($message->save()) {
            try {
                $type=1;
                $message->users()->attach($sender_id, ['group_id' => $request->group_id,'type'=>$type]);
                $sender = User::where('id', '=', $sender_id)->first();

                $data = [];
                $data['sender_id']      = $sender_id;
                $data['sender_name']    = $sender->first_name.' '.$sender->last_name;
                $data['group_id']       = $group_id;
                $data['type']           = 2;
                if($message->type == 2){
                    $data['content']    = "<audio controls src='".$message->message."' style='width:250px;'/></audio>";
                    $data['image']      = $image;
                    $data['file']    = 1;
                }else{
                    $data['content'] = $message->message;
                    $data['file']    = 0;
                    $data['image']   = $image;
                }

                $data['created_at']     = $message->created_at;
                $data['message_id']     = $message->id;

                event(new GroupMessageEvent($data));

                return response()->json([
                    'data' => $data,
                    'success' => true,
                    'message' => 'Message sent successfully'
                ]);
            } catch (\Exception $e) {
                $message->delete();
            }
        }
    }

    public function download( $id = '' )
    {
        $message = Message::select('message as file')->find($id);
        $filename = $message->file;
        $file_path = public_path('/uploads/chatImages/').$filename;
       // echo $file_path;die;
        $headers = array(
            'Content-Type: csv',
            'Content-Disposition: attachment; filename='.$filename,
        );
        if ( file_exists( $file_path ) ) {
            // Send Download
            return \Response::download( $file_path, $filename, $headers );
        } else {
             // Error
             exit( 'Requested file does not exist on our server!' );
        }
    }

    public function groupconversation($group_id=null){
        $chatgroup 	= ChatGroupMember::with('chatgroup')->where('group_member_id', Auth::id())->get();
      
        if($group_id != null){

            $currentgrp	= ChatGroup::find($group_id);
            $countCurrentGroup 	= ChatGroupMember::where('group_id', $group_id)->count();
            $friendInfo = $currentgrp;
            $messages 	= UserMessage::with('message','reciever_users','sender_users')->where('group_id',$group_id)->where('type',1)->get();
        }else{
            $currentgrp	        = collect([]);
            $countCurrentGroup	= collect([]);
            $friendInfo	        = collect([]);
            $messages	        = collect([]);
        }


        $companyid = Auth::user()->company_id;
        $users = User::where('id', '!=', Auth::id())->where('company_id',$companyid)->get();

        $myInfo 	= User::find(Auth::id());

        //echo '<pre>';print_r($messages->toArray());die;
        $this->data['users']        = $users;
        $this->data['friendInfo']   = $friendInfo;
        $this->data['myInfo']       = $myInfo;
        $this->data['users']        = $users;
        $this->data['messages']     = $messages;
        $this->data['chatgroup']    = $chatgroup;
        $this->data['chattype']     = 'group';
        $this->data['currentgrp']   = $currentgrp;
        $this->data['countCurrentGroup']   = $countCurrentGroup;

        return view('admin.message.groupconversation', $this->data);
    }
}
