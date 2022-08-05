<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserCompany;
use App\Models\User;
use App\Models\CompanyRole;
use App\Models\Department;
use App\Models\VstModule;
use App\Models\UserPermission;
use App\Models\Memo;
use Hash;
use Session;
use Mail;
use App\Mail\RegisterEmail;
use Auth;
class CompanyController extends Controller
{
    public function companies(Request $request)
    {
    	 $data = UserCompany::orderBy('id','DESC');

        if($request->company_name!=""){
          $data->where('company_name','LIKE','%'.$request->company_name.'%');
        }

        if($request->email!="")
        {
          $keyword = $request->email;
          $data->whereHas('user', function($q) use($keyword){
            $q->where('email',$keyword);
          });
        }

         $company_details = $data->paginate(env('PAGINATION_COUNT'));
    	 return view('admin/companies',compact('company_details'));
    }
    public function add_companies(Request $request)
    {
    	return view('admin/add_companies');
    }
    public function save_company(Request $request)
    {
        validator($request->all(), [
            'email' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:users,email'
        ])->validate();

    	$user = new User;
        $user->first_name 		= $request->first_name;
        $user->last_name 		= $request->last_name;
        $user->phone_number 	= $request->phone_number;
        $user->email 		    = $request->email;
        $user->role             = "2";
        $user->status           = "1";
        if($request->c_password != $request->password){
            return redirect()->back()->with('message', 'Password do not match!');
            exit();
        }
        $user->password 	    = Hash::make($request->password);
        $user->save();
        Mail::to($request->email)->send(new RegisterEmail($request->first_name,$request->last_name,$request->email,$request->password));
        if($user){
        	$userCompany = new UserCompany;
	        $userCompany->user_id      		     = $user->id;
	        $userCompany->company_name   		 = $request->company_name;
	        $userCompany->company_number         = $request->company_number;
	        $userCompany->street 				 = $request->street;
	        $userCompany->city 				     = $request->city;
	        $userCompany->state 				 = $request->state;
	        $userCompany->zip 					 = $request->zip;
	        $userCompany->country 				 = $request->country;
	        $userCompany->save();
            $user->company_id                    = $userCompany->id;
            $user->save();
	        return redirect('/admin/companies')->with('message', 'Company Added successfully!');
        }
    }

    public function edit_company($id)
    {
    	$company_detail = UserCompany::where('user_id', $id)->first();
        if($company_detail){
            return view('admin/edit_company',compact('company_detail'));
        }
        return redirect('/admin/user')->with('error', 'Not able to find company!');
    }

    public function view_company($id)
    {
    	$company_detail = UserCompany::where('id', $id)->first();
        return view('admin/view_company',compact('company_detail'));
    }

    public function update_company(Request $request)
    {
        validator($request->all(), [
            'email' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:users,email,'.$request->company_id
        ])->validate();
       
    	user::where('id', $request->company_id)->update(array(
                    'first_name'        =>    $request->first_name,
                    'last_name'         =>    $request->last_name,
                    'phone_number'      =>    $request->phone_number,
                    'email'             =>    $request->email,
                  ));

    	UserCompany::where('user_id', $request->company_id)->update(array(
                    'company_name'          =>    $request->company_name,
                    'company_number'        =>    $request->company_number,
                    'street'                =>    $request->street,
                    'city'        			=>    $request->city,
                    'state'      		    =>    $request->state,
                    'zip'        			=>    $request->zip,
                    'country'       	    =>    $request->country,
                  ));
        return redirect('/admin/companies')->with('message', 'Company updated successfully!');

    }

    public function detele_company($id)
    {

        $user_delete = User::find($id)->delete();
        if($user_delete){
            UserCompany::where('user_id',$id)->delete();
        }
    	return redirect()->back()->with('message', 'Delete record successfully!');
    }

    public function status_change(Request $request)
    {
    	 User::where('id', $request->id)->update(array(
                    'status'        =>    $request->status,
                  ));
    }

    public function check_email(Request $request)
    {
        if(isset($request->userid)){
            $check_email = User::where('email',$request->email)->where('id','!=',$request->userid)->count();
        }else{
            $check_email = User::where('email',$request->email)->count();
        }
        echo $check_email;
    }

    public function edit_email(Request $request)
    {
         $check_email = User::where('email',$request->email)
                        ->where('id','!=', $request->idd)
                        ->count();
         echo $check_email;
    }

    public function user(Request $request)
    {
        $userid = Auth::user()->id;
        $data = User::with('company_role')->where('company_id',Auth::user()->company_id);

        if($request->email!=""){
            $data->where('email',$request->email);
        }

        if($request->name!=""){
            $data->where('first_name','LIKE','%'.$request->name.'%');
        }
       

        $users_data =  $data->orderby('id','DESC')->paginate(env('PAGINATION_COUNT'));
        return view('admin/userlist',compact('users_data','userid'));
    }

    public function user_create(Request $request)
    {
        $roles = CompanyRole::where('company_id',Auth::user()->company_id)->get();
        $departments = Department::where('company_id',Auth::user()->company_id)->get();
        $vstmodules = VstModule::get();
        return view('admin/create_user',compact('roles','departments','vstmodules'));
    }

    public function edit_user($userid)
    {
        $user = User::with('permissions')->where('id',$userid)->first();
        // echo "<pre>";
        // print_r($user->toArray());die;
        $roles = CompanyRole::where('company_id',Auth::user()->company_id)->get();
        $departments = Department::get();
        $vstmodules = VstModule::get();
        return view('admin/edit_user',compact('roles','user','departments','vstmodules'));
    }

    public function save_user(Request $request)
    {
        validator($request->all(), [
            'email' => 'required|email|unique:users|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix'
        ])->validate();
        $company = UserCompany::where('user_id', Auth::user()->id)->first();

        $user = new User;
        $user->first_name       = $request->first_name;
        $user->last_name        = $request->last_name;
        $user->phone_number     = $request->phone_number;
        $user->email            = $request->email;
        $user->department_id    = $request->department_id;
        $user->role             = "3";
        $user->status           = "1";
        $user->company_id       = Auth::user()->company_id;
        $user->company_role_id  = $request->role_id;
        if($request->c_password != $request->password){
            return redirect()->back()->with('message', 'Password do not match!');
            exit();
        }
        $user->password         = Hash::make($request->password);
        $user->save();
        if($user){
           if($request->permissions){
            foreach ($request->permissions as $value) {
                    $user_permission = new UserPermission;
                    $user_permission->module_id = $value;
                    $user_permission->user_id = $user->id;
                    $user_permission->save();
                   
            }
           }
            
         Mail::to($request->email)->send(new RegisterEmail($request->first_name,$request->last_name,$request->email,$request->password));
            return redirect('/admin/user')->with('message', 'User Added successfully!');
        }
    }
    public function update_user(Request $request)
    {  
        $user = User::find($request->userid);
        // $request->validate([
        // // 'email' => 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix'
        // // ]);
        // 'email' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:users,email,'.$request->userid ]);

        validator($request->all(), [
            'email' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:users,email,'.$request->userid
        ])->validate();

        $check_email = User::where('email',$request->email)->first();

      // print_r($request->all());die;
       // if($check_email->id != $request->userid){
       //  if($check_email!=""){
       //       return redirect()->back()->with('message', 'Email Already Exits!');
       //       exit();
       //  }
       // }
        if($user){
            $user->first_name       = $request->fname;
            $user->last_name        = $request->lname;
            $user->phone_number     = $request->phone;
            $user->email            = $request->email;
            $user->company_role_id  = $request->role_id;
            $user->department_id    = $request->department_id;
            if($request->c_password != $request->password){
                return redirect()->back()->with('message', 'Password do not match!');
                exit();
            }
            if($request->password != ''){
                $user->password         = Hash::make($request->password);
            }

            $user->save();
            UserPermission::where('user_id',$request->userid)->forceDelete();           
            if(!empty($request->permissions)){
               foreach ($request->permissions as $value) {
                    $getdata = UserPermission::where('user_id',$request->userid)->where('module_id',$value)->first();
                    if($getdata==""){
                        $user_permission = new UserPermission;
                        $user_permission->module_id = $value;
                        $user_permission->user_id = $request->userid;
                        $user_permission->save();
                    }
                } 
            }
             
        }

        return redirect('/admin/user')->with('message', 'User updated successfully!');
    }

    public function role(Request $request)
    {
        //$companyid = Auth::user()->company_id;
        $data = CompanyRole::where('company_id',Auth::user()->company_id);

        if($request->name!=""){
            $data->where('name','LIKE','%'.$request->name.'%');
        }

        $roles = $data->orderBy('id','DESC')->paginate(env('PAGINATION_COUNT'));
        return view('admin/role_list',compact('roles'));
    }

    public function detele_role_user($id)
    {
       $user = User::find($id)->delete();
       if($user)
       {
        UserCompany::where('user_id',$id)->delete();
       }
       return redirect()->back()->with('message', 'Delete record successfully!');
    }

    public function view_role_user($id)
    {
        $role_detail = User::where('id', $id)->first();
        return view('admin/view_role',compact('role_detail'));
    }

    public function save_role(Request $request)
    {
        //$company = UserCompany::where('user_id', Auth::user()->id)->first();
        $user = new CompanyRole;
        $user->name             = $request->name;
        $user->description      = $request->description;
        $user->company_id       = Auth::user()->company_id;
        $user->save();
        return redirect()->back()->with('message', 'Role Added successfully!');
    }

    public function detele_role($id)
    {
        CompanyRole::find($id)->delete();
        return redirect()->back()->with('message', 'Record deleted successfully!');
    }

    public function update_roless(Request $request)
    {
        CompanyRole::where('id', $request->idd)->update(array(
                    'name'           =>    $request->name,
                    'description'    =>    $request->description,
                  ));
        return redirect()->back()->with('message', 'Record updated successfully!');
    }
    public function get_role(Request $request) {
        $id = $request->roleId;
        $data = CompanyRole::where('id',$id)->first();
        if(!$data){
            $status = 0;
            $message = "Role is either removed or not found";
        }else{
            $status = 1;
            $message = "Data found successfully";
        }
        return response()->json([
            'data' => $data,
            'status' => $status,
            'message' => 'Data found successfully'
        ]);
    }
    
    public function paint_image_uploade(Request $request)
    {
          $img = $request->image;
          if($img!=""){
              $folderPath = public_path('paintImage/');
              $image_parts = explode(";base64,", $img);
              $image_type_aux = explode("image/", $image_parts[0]);
              $image_type = $image_type_aux[1];
              $image_base64 = base64_decode($image_parts[1]);
              $filename = strtotime("now").rand(1111,9999) . '.'.$image_type;
              $file = $folderPath . $filename;
              file_put_contents($file, $image_base64);
              echo $filename;
          } 
    }

    public function deletePaintImage(Request $request)
    {
        Memo::where('image', $request->img_name)->update(['image' => null]);
        unlink('paintImage/'.$request->img_name);
    }

  
    
}
