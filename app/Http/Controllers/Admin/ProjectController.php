<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserCompany;
use App\Models\User;
use App\Models\ProjectType;
use App\Models\Project;
use App\Models\CompanyRole;
use Hash;
use Session;
use Mail;
use App\Mail\RegisterEmail;
use Auth;
use App\Models\ChatGroup;
use App\Models\ChatGroupMember;
use App\Models\ProjectAttachments;
use ZipArchive;
use Storage;

class ProjectController extends Controller
{
    public function project_list(Request $request)
    {
        $role     = Auth::user()->role;
        if($role != 3){
            $projects = Project::where('company_id',Auth::user()->company_id);
        }else{
            $userid = Auth::id();
            $projects = Project::wherehas('projectUsers', function($q) use($userid){
                $q->where('user_id', $userid);
            });
        }
        if($request->search!=""){
            $keyword = $request->search;
            // $projects->whereHas('projectName', function($q) use($keyword){
            // $q->where('name','LIKE','%'.$keyword.'%');            
          // });
        $projects->where('name','LIKE','%'.$keyword.'%');

        }
        $list_projects = $projects->orderby('id','DESC')->paginate(env('PAGINATION_COUNT'));
        return view('admin.project.project_list',compact('list_projects','role'));
    }

    public function project_create(Request $request)
    {
        $companyid = Auth::user()->company_id;
        $users_data = User::where('id', '!=', Auth::id())->where('company_id',$companyid)->get();
        $list_project_types = ProjectType::where('status',1)->where('company_id',$companyid)->orderby('id','DESC')->get();
        return view('admin.project.project_create',compact('list_project_types','users_data'));
    }

    public function project_type(Request $request)
    {
        $projecttypes = ProjectType::where('created_by',Auth::user()->id);

         if($request->search!=""){
            $projecttypes->where('name',$request->search);
         }

        $project_types = $projecttypes->orderby('id','DESC')->paginate(env('PAGINATION_COUNT'));
        return view('admin.project.project_type',compact('project_types'));
    }

    public function save_project_type(Request $request)
    {

        $user_id    = Auth::user()->id;
        $company_id = Auth::user()->company_id;

        if($user_id == "" || $company_id == "")
        {
            return redirect()->back()->with('message', 'Error');
            exit;
        }

        $project_type = new ProjectType;
        $project_type->created_by             = $user_id;
        $project_type->company_id             = $company_id;
        $project_type->status                 = $request->status;
        $project_type->name                   = $request->name;
        $project_type->description            = $request->description;
        $project_type->save();
        return redirect()->back()->with('message', 'Project type add successfully!');

    }

    public function update_project_type(Request $request)
    {
    	ProjectType::where('id', $request->idd)->update(array(
                    'name'        =>    $request->name,
                    'description' =>    $request->description,
                    'status'      =>    $request->status,
                  ));

    	return redirect()->back()->with('message', 'Project type updated successfully!');
    }

   public function detele_project_type($id)
   {
   	    ProjectType::find($id)->delete();
        return redirect()->back()->with('message', 'Delete record successfully!');
   }

   public function save_project(Request $request)
   {
        $user_id    = Auth::user()->id;
        $company_id = Auth::user()->company_id;

        if($user_id == "" || $company_id == "")
        {
            return redirect()->back()->with('message', 'Error');
            exit;
        }

        $project = new Project;
        $project->created_by          = $user_id;
        $project->company_id          = $company_id;
        $project->project_type_id     = $request->project_type_id;
        $project->name                = $request->name;
        $project->description         = $request->description;
        $project->project_status      = $request->status;
        $project->start_date          = $request->start_date;
        $project->end_date            = $request->end_date;
        $project->save();
        $project->projectUsers()->sync($request->users);

        // if($project){

        //     $images=array();
        //     if($files=$request->file('file')){
        //         foreach($files as $file){
        //            $name=time().rand(1111,9999).'_project'.'.'.$file->getClientOriginalExtension();
        //             $destinationPath = public_path('/project_attachments/');
        //             $file->move($destinationPath, $name);
        //             $images[]=$name;
        //         }
        //     }

        //     $project_attachments = new ProjectAttachments;
        //     $project_attachments->project_id    =  $project->id;
        //     $project_attachments->title         =  $request->title;
        //     $project_attachments->attachment    =  json_encode($images);
        //     $project_attachments->uploaded_by   =  $user_id;
        //     $project_attachments->save();
        // }
        return redirect('/admin/project_list')->with('message', 'Project created successfully!');
   }

   public function detele_project($id)
   {
    Project::find($id)->delete();
    return redirect()->back()->with('message', 'Delete record successfully!');
   }

    public function edit_project($id) {
        $companyid = Auth::user()->company_id;
        $users_data = User::where('id', '!=', Auth::id())->where('company_id',$companyid)->get();
        $list_project_types = ProjectType::where('status',1)->orderby('id','DESC')->get();
        $data = Project::with('projectUsers','projectAttachment','userFillName.user')->where('id',$id)->first();
        $projectusers = $data->projectUsers->pluck('id')->toArray();
        return view('admin.project.project_update',compact('list_project_types','data','users_data','projectusers'));
    }


    public function update_project(Request $request) {

        $project = Project::find($request->idd);
        if($project){
            $project->project_type_id     = $request->project_type_id;
            $project->name                = $request->name;
            $project->description         = $request->description;
            $project->project_status      = $request->status;
            $project->start_date          = $request->start_date;
            $project->end_date            = $request->end_date;
            $project->save();
            $project->projectUsers()->sync($request->users);
        }


        /*$images=array();
        if($files=$request->file('file')){
            foreach($files as $file){
                $name=time().rand(1111,9999).'_project'.'.'.$file->getClientOriginalExtension();
                $destinationPath = public_path('/project_attachments/');
                $file->move($destinationPath, $name);
                $images[]=$name;
            }
        }
        if(($request->title)!="" || (!empty($images))){
            $project_attachment =new ProjectAttachments;
            $project_attachment->project_id    =  $project->id;
            $project_attachment->uploaded_by   =  $project->created_by;
            $project_attachment->title         =  $request->title;
            if(!empty($images)){
                $project_attachment->attachment    =  json_encode($images);
            }
            $project_attachment->save();
        }*/

        return redirect('/admin/project_list')->with('message', 'Project updated successfully!');

    }
    public function get_project_type(Request $request) {
        $id = $request->projecTypeId;
        $data = ProjectType::where('id',$id)->first();
        if(!$data){
            $status = 0;
            $message = "Project type is either removed or not found";
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
    public function calendar_list(){
        return view('admin.calendar');
    }

    public function uploade_attachment($id)
    {
       $data = ProjectAttachments::where('id',$id)->first();
        $files = json_decode($data['attachment']);
        // foreach ($files as $file) {
        //    $pdf = public_path('project_attachments/'.$file);
        //         return response()->download($pdf);
        // }

     $zip = new ZipArchive();
    $tempFile = 'fileAttachments.zip';
    $zipPathAndName = public_path('project_attachments/'.'16239235794510_project.jpg');
    //$tempFileUri = stream_get_meta_data($tempFile)['uri'];
    if ($zip->open($tempFile, ZipArchive::CREATE) === TRUE) {
        // Add File in ZipArchive
        foreach($files as $file)
        {
            if (! $zip->addFile($file, basename($file))) {
                echo 'Could not add file to ZIP: ' . $file;
            }
        }
        // Close ZipArchive
        $zip->close();
    } else {
        echo 'Could not open ZIP file.';
    }
    echo 'Path:' . $zipPathAndName;
    rename($tempFile, $zipPathAndName);


       //  $zip_file = 'fileAttachments.zip';
       //  $zip = new \ZipArchive();
       //  $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

       // $path = public_path('project_attachments');

       //  $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
       //  foreach ($files as $name => $file)
       //  {
       //      if (!$file->isDir()) {
       //          $filePath     = $file->getRealPath();
       //          $relativePath = substr($filePath, strlen($path) + 1);
       //          $zip->addFile($filePath, $relativePath);
       //      }
       //  }
       //  $zip->close();
       //  return response()->download($zip_file);


    }

    public function attachment_rm(Request $request){
        $file = $request->file('file');
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            return $media_filename = uniqid(time()) . '.' . $extension;
        }
        print_r($request->all());
    }

    public function projectsAll(Request $request){
        $project_id = $request->project_id;
        session(['project_id' => $project_id]); 
    }

}
