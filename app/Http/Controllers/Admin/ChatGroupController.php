<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\UserMessage;
use App\Models\User;
use App\Models\ChatGroup;
use App\Models\ChatGroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\PrivateMessageEvent;
use File;
use Illuminate\Http\Response;

class ChatGroupController extends Controller
{
    public function checkGroup(Request $request){
        $groupname  = $request->group;
        $user_id    = $request->user_id;
        $chatgroup  = ChatGroup::where('group_name',$groupname)->first();
        $message    ='Group is already exists.';
        $status     = 0;
        if(!$chatgroup){
            $chatgroup =  new ChatGroup();
            $chatgroup->group_name  = $groupname;
            $chatgroup->user_id     = $user_id;
            $chatgroup->save();

            $checkgroup =  new ChatGroupMember();
            $checkgroup->group_id  = $chatgroup->id;
            $checkgroup->group_member_id  = $request->user_id;
            $checkgroup->user_id     = Auth::id();
            $checkgroup->save();


            $message='Group created successfully.';
            $status = 1;
        }
        return response()->json(['status'=>$status, 'message' => $message, 'data' => $chatgroup]);
    }
    public function addUserToGroup(Request $request){
        $checkgroup = ChatGroupMember::where('group_id',$request->groupId)->where('group_member_id',$request->user_id)->first();
        if(!$checkgroup){
            $checkgroup =  new ChatGroupMember();
            $checkgroup->group_id  = $request->groupId;
            $checkgroup->group_member_id  = $request->user_id;
            $checkgroup->user_id     = Auth::id();
            $checkgroup->save();
            $message='User added successfully.';
            $status = 1;
        }else{
            $checkgroup->delete();
            $message='User removed successfully.';
            $status = 0;
        }
        return response()->json(['status'=>$status, 'message' => $message, 'data' => $checkgroup]);
    }
    public function getUsersFromGroup(Request $request){
        $groupusers = ChatGroupMember::where('group_id',$request->groupId)->pluck('group_member_id');
        return response()->json(['status'=>1, 'message' => 'Users found successfully', 'data' => $groupusers]);
    }
}
