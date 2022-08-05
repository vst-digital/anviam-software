<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserCompany;
use App\Models\User;
use App\Models\ProjectType;
use App\Models\Project;
use App\Models\Memo;
use App\Models\MemoThread;
use App\Models\CompanyRole;
use App\Models\UserMessage;
use App\Models\Message;
use App\Models\Document;
use App\Models\DocumentThreads;
use App\Models\DocumentsFolders;
use Hash;
use Session;
use Mail;
use Auth;
use App\Mail\MemoEmail;
use App\Mail\ReplyMemoEmail;
use App\Models\ChatGroup;
use App\Models\ChatGroupMember;
use File;

class StorageController extends Controller
{
    public function storage_list_chat(Request $request) {
        if($request->user_id != ""){
            $chatimages 	= UserMessage::with('message')->wherehas('message', function($q) {
                $q->where('type', 2);
            })->where('sender_id',Auth::id())->where('receiver_id',$request->user_id)->orWhere('receiver_id',Auth::id())->where('sender_id',$request->user_id)->where('type',0)->get();
        }else{
            $chatimages 	= UserMessage::with('message')->wherehas('message', function($q) {
                $q->where('type', 2);
            })->where('sender_id',Auth::id())->orWhere('receiver_id',Auth::id())->where('type',0)->get();
        }

        $companyid = Auth::user()->company_id;
        $users = User::where('id', '!=', Auth::id())->where('company_id',$companyid)->get();
        return view('admin.storage.storage_list_chat',compact('chatimages','users'));
    }
    public function storage_list_group(Request $request) {
        if($request->group_id != ""){
            $chatgroup 	= array($request->group_id);
        }else{
            $chatgroup 	= ChatGroupMember::with('chatgroup')->where('group_member_id', Auth::id())->pluck('group_id')->toArray();
        }


        $groupimages 	= UserMessage::with('message')->wherehas('message', function($q) {
            $q->where('type', 2);
        })->whereIn('group_id',$chatgroup)->where('type',1)->get();
        $chatgroups 	= ChatGroupMember::with('chatgroup')->where('group_member_id', Auth::id())->get();
        return view('admin.storage.storage_list_group',compact('groupimages','chatgroups'));
    }
    public function storage_list_memo(Request $request) {
        $userid = Auth::id();
        $memos = Memo::wherehas('memoUsers', function($q) use($userid){
            $q->where('user_id', $userid);
        })->orWhere('created_by',$userid)->groupBy('id')->get();
        if($request->memo_id == ""){
            $memo = Memo::with('memoThreads')->wherehas('memoUsers', function($q) use($userid){
                    $q->where('user_id', $userid);
                })->orWhere('created_by',$userid)->get();
        }else{
            $memo = Memo::with('memoThreads')->where('id',$request->memo_id)->get();
        }
        //echo '<pre>'; print_r($memo->toArray());echo '</pre>';die;
        return view('admin.storage.storage_list_memo',compact('memo','memos'));
    }
    public function storage_list_documents(Request $request) {
        $companyid  = Auth::user()->company_id;
        $role       = Auth::user()->role;
        $userid     = Auth::id();

        // if($request->document_id != ""){
        //     $documents->where('id',$request->document_id);
        // }
        // if($request->folder != ""){
        //     $folder = $request->folder;
        //     $documents->where('folder',$request->folder)->wherehas('DocumentThreads', function($q) use($folder){
        //         $q->orWhere('folder', $folder); });
        // }
        if($request->document_id == "" && $request->folder == ""){
            $documents = Document::with('folderData','DocumentThreads.threadFolderData')->wherehas('DocumentThreads', function($q) use($userid){
                $q->orWhere('uploaded_by', $userid)->orWhereJsonContains('users',"$userid");
            })->orWhere('created_by',$userid);

        }else{
            $documents = Document::with('folderData','DocumentThreads.threadFolderData');
            if($request->document_id != ""){
                $documents->where('id',$request->document_id);
            }
            if($request->folder != ""){
                $folder = $request->folder;
                $documents->where('folder',$folder)->orWherehas('DocumentThreads', function($q) use($folder){
                    $q->where('folder', $folder);
                });
            }
        }
        $documents = $documents->groupBy('id')->get();
        $alldocuments  = Document::with('projectName','DocumentThreads')->where(function($query) use ($userid){
            $query->orWhere('created_by',$userid)->orWhereJsonContains('users',"$userid");
        })->get();
        $folders = DocumentsFolders::where('user_id',Auth::id())->get();
        // echo '<pre>'; print_r($documents->toArray());echo '</pre>';die;
        return view('admin.storage.storage_list_documents',compact('documents','alldocuments','folders'));
    }
}
