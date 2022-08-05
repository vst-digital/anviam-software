<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Project;
use App\Models\ChatGroup;
use App\Models\ChatGroupMember;
use Carbon\Carbon;
use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function admin_login() {
       $total_user = User::where('company_id', Auth::user()->company_id)
                     ->where('id','!=',Auth::user()->id)
                     ->count();
       $total_project = Project::where('created_by', Auth::user()->id)->count();
        
        /*user chert code*/

        $total_chart_user =  array();
        $users = User::select('id', 'created_at')
         ->where('company_id', Auth::user()->company_id)
             ->where('id','!=',Auth::user()->id)
        ->get()
        ->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('m'); 
        });
        $usermcount = [];
        $userArr = [];
        foreach ($users as $key => $value) {
            $usermcount[(int)$key] = count($value);
        }
        for($i = 1; $i <= 12; $i++){
            if(!empty($usermcount[$i])){
                $userArr[$i] = $usermcount[$i];  
                array_push($total_chart_user,$userArr[$i]);
            }else{
                $userArr[$i] = 0; 
                 array_push($total_chart_user,$userArr[$i]);
            }
        } 

      /*project chert code*/ 
       
        $total_chart_project =  array();
        $projects = Project::select('id', 'created_at')
                ->where('created_by', Auth::user()->id)
                ->get()
                ->groupBy(function($date) {
            //return Carbon::parse($date->created_at)->format('Y'); // grouping by years
            return Carbon::parse($date->created_at)->format('m'); // grouping by months
        });
        $usermcount = [];
        $userArr = [];
        foreach ($projects as $key => $project) {
            $usermcount[(int)$key] = count($project);
        }
        for($i = 1; $i <= 12; $i++){
            if(!empty($usermcount[$i])){
                $userArr[$i] = $usermcount[$i];  
                array_push($total_chart_project,$userArr[$i]);
            }else{
                $userArr[$i] = 0; 
                 array_push($total_chart_project,$userArr[$i]);
            }
        } 

        $user_chart = json_encode($total_chart_user);
        $project_chart = json_encode($total_chart_project);
         return view('home',compact('total_user','total_project','user_chart','project_chart'));
    }
}

