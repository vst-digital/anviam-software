<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserCompany;
use App\Models\User;
use App\Models\ProjectType;
use App\Models\Project;
use App\Models\CompanyRole;
use App\Models\Memo;
use App\Models\Event;
use Hash;
use Session;
use Mail;
use App\Mail\EventEmail;
use Auth;
use App\Models\ChatGroup;
use App\Models\ChatGroupMember;

class CalendarController extends Controller {
    public function calendar_list() {

        $userid = Auth::id();
        $memoids = Event::where(function($query) use ($userid){
                        $query->where('created_by',$userid)->orWhereJsonContains('users',"$userid");
                    })->where('references','!=',0)->where('referenceto',1)->pluck('references')->toArray();
        $projectid = Event::where(function($query) use ($userid){
                        $query->where('created_by',$userid)->orWhereJsonContains('users',"$userid");
                    })->where('references','!=',0)->where('referenceto',2)->pluck('references')->toArray();

        $nonUserMemo = Memo::whereIn('id',$memoids)->orderby('id','DESC')->get();
        $nonuserprojects = Project::whereIn('id',$projectid)->orderby('id','DESC')->get();
        //echo '<pre>';print_r($nonUserMemo->toArray());die;
        $companyid  = Auth::user()->company_id;
        $users_data = User::where('company_id',$companyid)->get();
        $role       = Auth::user()->role;
        if($role != 3){
            $memo = Memo::where('company_id',Auth::user()->company_id)->orderby('id','DESC')->get();
            $list_projects = Project::where('company_id',$companyid)->orderby('id','DESC')->get();
        }else{
            $userid = Auth::id();
            $memo = Memo::wherehas('memoUsers', function($q) use($userid){
                $q->where('user_id', $userid);
            })->orderby('id','DESC')->get();
            $list_projects = Project::wherehas('projectUsers', function($q) use($userid){
                $q->where('user_id', $userid);
            })->orderby('id','DESC')->get();
        }
        //print_r($memo->toArray());die;
        return view('admin.calendar',compact('list_projects','users_data','memo','nonUserMemo','nonuserprojects'));
    }
    public function index(Request $request) {
    	if($request->ajax()) {
            $userid = Auth::id();
    		$data = Event::whereDate('start', '>=', $request->start)
                       ->whereDate('end',   '<=', $request->end)
                       ->where(function($query) use ($userid){
                           $query->where('created_by',$userid)->orWhereJsonContains('users',"$userid");
                       })
                       ->get(['id', 'color', 'title', 'start', 'end', 'description', 'users', 'referenceto', 'references', 'created_by']);
            return response()->json($data);
    	}

    }

    public function action(Request $request) {
    	if($request->ajax()) {
            $userid = Auth::id();
    		if($request->type == 'add') {
                $users = json_encode('');
                $userarr = array();
                if($request->users != ''){
                    $userarr    = explode(',',$request->users);
                    $users      = json_encode($userarr);
                }
                //echo '<pre>';print_r($request->all());die;
                $reference = 0;
                $referencemail = '';
                if($request->referenceto == 1){
                    $memo      = Memo::where('id',$request->memos)->first();
                    $referencemail = $memo->subject;
                    $reference = $request->memos;
                }else if($request->referenceto == 2){
                    $project = Project::find($request->projects);
                    $referencemail = $project->name;
                    $reference = $request->projects;
                }
    			$event = Event::create([
    				'title'		    =>	$request->title,
					'color'		    =>	$request->color,
    				'start'		    =>	date('Y-m-d H:i:s', strtotime($request->start)),
    				'end'		    =>	date('Y-m-d H:i:s', strtotime($request->end)),
                    'description'	=>	$request->description,
                    'referenceto'	=>	$request->referenceto,
                    'references'	=>	$reference,
                    'users'		    =>	$users,
                    'created_by'    =>	$userid
    			]);
                array_push($userarr,$userid);
                $userarr = array_unique($userarr);
                $usersdata  = User::whereIn('id',$userarr)->get();
                $meetingcreater = Auth::user()->first_name.' '.Auth::user()->last_name;
                if($usersdata) {
                    foreach($usersdata as $val) {
                        if($val->email != '') {
                            Mail::to($val->email)->send(new EventEmail($val->first_name,$val->last_name,$meetingcreater,$request->title,$request->description,$request->referenceto,$referencemail,$request->start,$request->end));
                        }
                    }
                }
    			return response()->json($event);
    		}

    		if($request->type == 'update') {
                $users = json_encode('');
                $userarr = array();
                if($request->users != ''){
                    $userarr    = explode(',',$request->users);
                    $users      = json_encode($userarr);
                }
                $reference = 0;
                if($request->referenceto == 1){
                    $reference = $request->memos;
                }else if($request->referenceto == 2){
                    $reference = $request->projects;
                }
    			$event = Event::find($request->id)->update([
    				'title'		    =>	$request->title,
    				'start'		    =>	date('Y-m-d H:i:s', strtotime($request->start)),
    				'end'		    =>	date('Y-m-d H:i:s', strtotime($request->end)),
                    'description'	=>	$request->description,
                    'referenceto'	=>	$request->referenceto,
                    'references'	=>	$reference,
                    'users'		    =>	$users,
    			]);

    			return response()->json($event);
    		}

    		if($request->type == 'delete') {
    			$event = Event::find($request->id)->delete();
    			return response()->json($event);
    		}
    	}
    }
}
