<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Auth;
use App\Models\Department;
use Redirect;
class DepartmentController extends Controller
{
    public function department(Request $request){
    	$query = Department::orderBy('id','desc')->where('company_id',Auth::user()->company_id);
    	if($request->name!=""){
    		$keyword = $request->name;
    		$query->where('name','LIKE','%'.$keyword.'%');
    	}
    	$department_lists = $query->paginate(env('PAGINATION_COUNT'));
    	return view('department/list',compact('department_lists'));
    }

    public function department_create(Request $request){
    	return view('department/add_department');
    }

    public function save_department(Request $request){
    	$user_id    = Auth::user()->id;
    	$department = new Department;
    	$department->name = $request->name;
    	$department->description = $request->description;
        $department->company_id = Auth::user()->company_id;
    	$department->created_by = $user_id;
    	$department->save();
    	if($department){
    		return Redirect::route('department')->with('message', 'Department create successfully!');

    	}
    }

    public function edit_department($id){
    	$department = Department::find($id);
    	if($department){
    		return view('department/edit_department',compact('department'));
    	}
    }

    public function update_department(Request $request){
    	$department = Department::find($request->id);
    	$department->name = $request->name;
    	$department->description = $request->description;
    	$department->save();
    	if($department){
    		return Redirect::back()->with('message', 'Department updated successfully!');

    	}
    }

    public function detele_department($id){
    	$department = Department::find($id);
    	if($department){
    		Department::where('id',$department->id)->delete();
    		return Redirect::back()->with('message', 'Department deleted successfully!');
    	}
    }
}
