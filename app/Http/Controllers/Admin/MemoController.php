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
use Hash;
use Session;
use Mail;
use Auth;
use App\Mail\MemoEmail;
use App\Mail\ReplyMemoEmail;
use App\Models\ChatGroup;
use App\Models\ChatGroupMember;
use File;
 
class MemoController extends Controller
{
    public function memo_list(Request $request) {
        $companyid  = Auth::user()->company_id;
        $role     = Auth::user()->role;        
        $project_id = Session::get('project_id');   
        if($role != 3){
            if(!empty($project_id)){
               $memo = Memo::with('projectName')->where('company_id',Auth::user()->company_id)
                             ->where('project_id',$project_id);
            }else{
             $memo = Memo::with('projectName')->where('company_id',Auth::user()->company_id);
            }
            $list_projects = Project::where('company_id',$companyid)->orderby('id','DESC')->get();
        }else{
            $userid = Auth::id();
            if(!empty($project_id)){
                    $memo = Memo::with('projectName')->wherehas('memoUsers', function($q) use($userid,$project_id){
                    $q->where('user_id', $userid);
                    $q->where('project_id', $project_id);
                 });
            }else{
                    $memo = Memo::with('projectName')->wherehas('memoUsers', function($q) use($userid){
                    $q->where('user_id', $userid);
                });
            }            
            $list_projects = Project::wherehas('projectUsers', function($q) use($userid){
                $q->where('user_id', $userid);
            })->orderby('id','DESC')->get();
        }
        if($request->project_id){
            $memo->where('project_id',$request->project_id);
        }
        if($request->search){
            $memo->where('project_number','like','%'.$request->search.'%');
        }
        $list_memos = $memo->orderby('id','DESC')->paginate(env('PAGINATION_COUNT'));
        return view('admin.memo.memo_list',compact('list_memos','role','list_projects'));
    }

    public function memo_create(Request $request) {
        $companyid  = Auth::user()->company_id;
        $role       = Auth::user()->role;
        if($role != 3){
            $list_projects = Project::where('company_id',$companyid)->orderby('id','DESC')->get();
        }else{
            $userid = Auth::id();
            $list_projects = Project::wherehas('projectUsers', function($q) use($userid){
                $q->where('user_id', $userid);
            })->orderby('id','DESC')->get();
        }
        $users_data = User::where('id', '!=', Auth::id())->where('company_id',$companyid)->get();
        return view('admin.memo.memo_create',compact('list_projects','users_data'));
    }

    public function save_memo(Request $request) {
        $user_id    = Auth::user()->id;
        $company_id = Auth::user()->company_id;
        // echo '<pre>'; print_r($request->all());die;
        if($user_id == "" || $company_id == "") {
            return redirect()->back()->with('message', 'Error');
            exit;
        }
        $tag = $attachment = array();
        if ($request->hasfile('attachment')) {
            $i=0;
            foreach($request->file('attachment') as $val){
                $file = $val;
                if ($file) {
                    $path = public_path("uploads/memo/attachment");
                    if (!File::isDirectory($path)) {
                        File::makeDirectory($path, 0777, true, true);
                    }
                    $extension = $file->getClientOriginalExtension(); // getting image extension
                    $media_filename = uniqid(time()).$i . '.' . $extension;
                    $file->move($path, $media_filename);
                    //$project->attachment= $media_filename;
                    array_push($attachment,$media_filename);
                    $i++;
                }
            }
        }

        if ($request->hasfile('tag')) {
            $i=0;
            foreach($request->file('tag') as $val){
                $file = $val;
                if ($file) {
                    $path = public_path("uploads/memo/tag");
                    if (!File::isDirectory($path)) {
                        File::makeDirectory($path, 0777, true, true);
                    }
                    $extension = $file->getClientOriginalExtension(); // getting image extension
                    $media_filename = uniqid(time()).$i . '.' . $extension;
                    $file->move($path, $media_filename);
                    array_push($tag,$media_filename);
                    $i++;
                }
            }
        }

        //echo '<pre>';print_r($users->toArray());die;
        $project = new Memo;
        $project->created_by        = $user_id;
        $project->company_id        = $company_id;
        $project->project_id        = $request->project_id;
        $project->project_number    = $request->project_number;
        $project->correspondence_no = $request->correspondence_no;
        $project->datetime          = $request->datetime;
        $project->subject           = $request->subject;
        $project->response          = $request->response;
        if(count($attachment) > 0){
            $project->attachment    = json_encode($attachment);
        }
        if(count($tag) > 0){
            $project->tag           = json_encode($tag);
        }


        $project->location          = $request->location;
        $project->memo              = $request->memo;
        $project->image              = $request->image;
        // echo "<pre>";
        // print_r($project);die;

        $project->save();
        $project->memoUsers()->sync($request->users);

        $project = Project::where('id',$request->project_id)->first();
        //echo $project->name;die;
        $users = User::whereIn('id',$request->users)->get();
        if($users){
            $attachment_img = array();
            $image = array();
            if(count($attachment) > 0){
                foreach($attachment as $val){
                    $image = public_path('uploads/memo/attachment/'.$val);
                    array_push($attachment_img,$image);
                }
            }
            if(count($tag) > 0){
                foreach($tag as $val){
                    $image = public_path('uploads/memo/tag/'.$val);
                    array_push($attachment_img,$image);
                }
            }
            if($request->image){               
                    $paintImage = public_path('paintImage/'.$request->image);                 
                    array_push($attachment_img,$paintImage);               
            }             
            foreach($users as $val){
                $firstname  = $val->first_name;
                $last_name  = $val->last_name;
                $email      = $val->email;
                Mail::to($email)->send(new MemoEmail($firstname,$last_name,$project->name,$request->subject,$request->project_number,$request->correspondence_no,$request->datetime,$request->response,$request->location,$request->memo,$attachment_img));
            }

        }

        return redirect('/admin/issue_list')->with('message', 'Issue created successfully!');
   }

   public function detele_memo($id) {
        Memo::find($id)->delete();
        return redirect()->back()->with('message', 'Delete record successfully!');
   }

    public function edit_memo($id) {
        $companyid  = Auth::user()->company_id;
        $role       = Auth::user()->role;
        $memo       = Memo::with('projectName','memoUsers')->where('id',$id)->first();
        $users_data = User::where('id', '!=', Auth::id())->where('company_id',$companyid)->get();
        //echo '<pre>';print_r($memo);die;
        $memousers  = $memo->memoUsers->pluck('id')->toArray();

        if($role != 3){
            $list_projects = Project::where('company_id',$companyid)->orderby('id','DESC')->get();
        }else{
            $userid = Auth::id();
            $list_projects = Project::wherehas('projectUsers', function($q) use($userid){
                $q->where('user_id', $userid);
            })->orderby('id','DESC')->get();
        }

        return view('admin.memo.memo_update',compact('list_projects','users_data','memousers','memo'));
    }


    public function update_memo(Request $request) {

        $memo = Memo::find($request->id);

        if($memo){
            $tag = $attachment = array();
            if ($request->hasfile('attachment')) {
                $i=0;
                foreach($request->file('attachment') as $val){
                    $file = $val;
                    if ($file) {
                        $path = public_path("uploads/memo/attachment");
                        if (!File::isDirectory($path)) {
                            File::makeDirectory($path, 0777, true, true);
                        }
                        $extension = $file->getClientOriginalExtension(); // getting image extension
                        $media_filename = uniqid(time()).$i . '.' . $extension;
                        $file->move($path, $media_filename);
                        //$project->attachment= $media_filename;
                        array_push($attachment,$media_filename);
                        $i++;
                    }
                }
            }

            if ($request->hasfile('tag')) {
                $i=0;
                foreach($request->file('tag') as $val){
                    $file = $val;
                    if ($file) {
                        $path = public_path("uploads/memo/tag");
                        if (!File::isDirectory($path)) {
                            File::makeDirectory($path, 0777, true, true);
                        }
                        $extension = $file->getClientOriginalExtension(); // getting image extension
                        $media_filename = uniqid(time()).$i . '.' . $extension;
                        $file->move($path, $media_filename);
                        array_push($tag,$media_filename);
                        $i++;
                    }
                }
            }
            if($memo->attachment != ''){
                $upatedearrattch        = array_merge($attachment,json_decode($memo->attachment));
            }else{
                $upatedearrattch        = $attachment;
            }
            if($memo->tag != ''){
                $upatedearrtag        = array_merge($tag,json_decode($memo->tag));
            }else{
                $upatedearrtag        = $tag;
            }
            // $upatedearrattch        = array_merge($attachment,json_decode($memo->attachment));
            // $upatedearrtag          = array_merge($tag,json_decode($memo->tag));
            $memo->project_id        = $request->project_id;
            $memo->project_number    = $request->project_number;
            $memo->correspondence_no = $request->correspondence_no;
            $memo->datetime          = $request->datetime;
            $memo->subject           = $request->subject;
            $memo->response          = $request->response;
            if(count($attachment) > 0){
                $memo->attachment    = json_encode($upatedearrattch);
            }
            if(count($tag) > 0){
                $memo->tag           = json_encode($upatedearrtag);
            }
            $memo->location          = $request->location;
            $memo->memo              = $request->memo;
            if($request->image!=''){
               $memo->image             = $request->image; 
            }
            


            $memo->save();
            $memo->memoUsers()->sync($request->users);
        }
        return redirect('/admin/issue_list')->with('message', 'Issue updated successfully!');

    }

    //Memo for thread of customer
    public function check_memo($id) {
        $companyid  = Auth::user()->company_id;
        $role       = Auth::user()->role;
        $memo       = Memo::with('projectName','memoUsers','memoThreads','memoThreads.memoUsers')->where('id',$id)->first();
        $users_data = User::where('company_id',$companyid)->get();
        //echo '<pre>';print_r($memo->toArray());die;
        $memousers  = $memo->memoUsers->pluck('id')->toArray();
        // echo '<pre>';print_r($memousers);die;
        if($role != 3){
            $list_projects = Project::where('company_id',$companyid)->orderby('id','DESC')->get();
        }else{
            $userid = Auth::id();
            $list_projects = Project::wherehas('projectUsers', function($q) use($userid){
                $q->where('user_id', $userid);
            })->orderby('id','DESC')->get();
        }

        return view('admin.memo.memo_thread',compact('list_projects','users_data','memousers','memo'));
    }
    //Memo thread post
    public function reply_memo(Request $request){
        validator($request->all(), [
            'memo' => 'required'
        ])->validate();
        $name   = Auth::user()->first_name.' '.Auth::user()->last_name;
        $tag    = $attachment = array();

        if ($request->hasfile('attachment')) {
            $i=0;
            foreach($request->file('attachment') as $val){
                $file = $val;
                if ($file) {
                    $path = public_path("uploads/memo/attachment");
                    if (!File::isDirectory($path)) {
                        File::makeDirectory($path, 0777, true, true);
                    }
                    $extension = $file->getClientOriginalExtension(); // getting image extension
                    $media_filename = uniqid(time()).$i . '.' . $extension;
                    $file->move($path, $media_filename);
                    //$project->attachment= $media_filename;
                  
                    array_push($attachment,$media_filename);
                    
                    $i++;
                }
               
            }
        }

        if ($request->hasfile('tag')) {
            $i=0;
            foreach($request->file('tag') as $val){
                $file = $val;
                if ($file) {
                    $path = public_path("uploads/memo/tag");
                    if (!File::isDirectory($path)) {
                        File::makeDirectory($path, 0777, true, true);
                    }
                    $extension = $file->getClientOriginalExtension(); // getting image extension
                    $media_filename = uniqid(time()).$i . '.' . $extension;
                    $file->move($path, $media_filename);
                    array_push($tag,$media_filename);
                    $i++;
                }
            }
        }
        $userid = Auth::id();

        $MemoThread = new MemoThread;
        if($attachment){
            $MemoThread->attachment    = json_encode($attachment);
        }
        if(count($tag) > 0){
            $MemoThread->tag           = json_encode($tag);
        }
        $MemoThread->memo              = $request->memo;
        $MemoThread->user_id           = $userid;
        $MemoThread->memo_id           = $request->memoid;
        $MemoThread->image             = $request->image; 

        $MemoThread->save();
        $memo       = Memo::with('projectName','memoUsers')->where('id',$request->memoid)->first();
        $memousers  = $memo->memoUsers->pluck('id')->toArray();
        $users      = User::whereIn('id',$memousers)->get();

        if($users){
            $attachment_img = array();
            $image = array();
            if(count($attachment) > 0){
                foreach($attachment as $val){
                    $image = public_path('uploads/memo/attachment/'.$val);
                    array_push($attachment_img,$image);
                }
            }
            if(count($tag) > 0){
                foreach($tag as $val){
                    $image = public_path('uploads/memo/tag/'.$val);
                    array_push($attachment_img,$image);
                }
            }
            if($image){               
                    $paintImage = public_path('paintImage/'.$request->image);                 
                    array_push($attachment_img,$paintImage);               
            }            
            foreach($users as $val){
                $firstname  = $val->first_name;
                $last_name  = $val->last_name;
                $email      = $val->email;
                Mail::to($email)->send(new ReplyMemoEmail($firstname,$last_name,$memo->projectName->name,$memo->subject,$memo->project_number,$memo->correspondence_no,$memo->datetime,json_decode($memo->response),$memo->location,$memo->memo,$request->memo,$name,$attachment_img));
            }
        }
        //echo '<pre>'; print_r($request->all());die;
        return redirect('/admin/issue_list')->with('message', 'Issue sent successfully!');
    }
}
