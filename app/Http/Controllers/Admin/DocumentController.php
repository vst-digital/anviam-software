<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserCompany;
use App\Models\User;
use App\Models\ProjectType;
use App\Models\Project;
use App\Models\CompanyRole;
use App\Models\Document;
use App\Models\DocumentThreads;
use App\Models\DocumentsFolders;
use App\Mail\DocumentsEmail;
use Hash;
use Session;
use Mail;
use Auth;
use File;
use ZipArchive;
class DocumentController extends Controller
{
    public function document_storage (Request $request) {
        $companyid  = Auth::user()->company_id;
        $role       = Auth::user()->role;
        $userid     = Auth::id();
        $project_id = Session::get('project_id'); 
        if(!empty($project_id)){
              $documents  = Document::with('projectName','DocumentThreads')->where(function($query) use ($userid,$project_id){
              $query->where('created_by',$userid)->where('project_id',$project_id)->orWhereJsonContains('users',"$userid");
          });
        }else{
            $documents  = Document::with('projectName','DocumentThreads')->where(function($query) use ($userid){
              $query->where('created_by',$userid)->orWhereJsonContains('users',"$userid");
          });
        }        
        // ->orWherehas('DocumentThreads', function($q) use($userid){
        //     $q->where('uploaded_by',$userid)->orWhereJsonContains('users',"$userid");
        // });;
        //print_R($documents);die;
        if($role != 3){
            $list_projects = Project::where('company_id',$companyid)->orderby('id','DESC')->get();
        }else{
            $list_projects = Project::wherehas('projectUsers', function($q) use($userid){
                $q->where('user_id', $userid);
            })->orderby('id','DESC')->get();
        }
        if($request->project_id){
            $documents->where('project_id',$request->project_id);
        }
        if($request->search){
            $documents->where('title','like','%'.$request->search.'%');
        }
        $documents = $documents->orderBy('updated_at','DESC')->paginate(env('PAGINATION_COUNT'));
        // echo '<pre>';print_r($documents->toArray());die;
        $users_data = User::where('id', '!=', Auth::id())->where('company_id',$companyid)->get();
        return view('admin.document.document_list',compact('role','list_projects','users_data','documents'));
    }

    public function document_create(Request $request) {
        $companyid  = Auth::user()->company_id;
        $role       = Auth::user()->role;
        $userid     = Auth::id();
        if($role != 3){
            $list_projects = Project::where('company_id',$companyid)->orderby('id','DESC')->get();
        }else{
            $list_projects = Project::wherehas('projectUsers', function($q) use($userid){
                $q->where('user_id', $userid);
            })->orderby('id','DESC')->get();
        }
        $folders = DocumentsFolders::where('user_id',Auth::id())->get();
        $users_data = User::where('id', '!=', Auth::id())->where('company_id',$companyid)->get();
        return view('admin.document.document_create',compact('list_projects','users_data','folders'));
    }
    public function attachment_add(Request $request){
        if ($request->file) {
            $file = $request->file;
            $path = public_path("uploads/documents/");
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $media_filename = uniqid(time()) . '.' . $extension;
            $file->move($path, $media_filename);
            return $media_filename;
        }
    }

    public function attachment_rm(Request $request){
        $filepath = public_path().'/uploads/documents/'.$request->fileList;
        if(\File::exists($filepath)){
            \File::delete($filepath);
        }else{
            return 'File does not exists.';
        }
        return true;
    }
    public function save_document(Request $request){
        validator($request->all(), [
            'title' => 'required'
        ])->validate();echo '<pre>';
        $userid = Auth::id();
        $filesarr = $users = array();
        $attachment_img = $rmimg = array();
        $folderName = '';
        if($request->folder != ''){
            $doc_indb = DocumentsFolders::where('id',$request->folder)->first();
            if($doc_indb){
                $folderName = $doc_indb->folder;
            }
        }
        if($request->attachments != ''){
            $newfiles = explode(',',$request->attachments);
            if(count($newfiles) > 0){
                foreach($newfiles as $val){
                    if(file_exists( public_path().'/uploads/documents/'.$val)){
                        array_push($filesarr,$val);
                        $image = public_path('/uploads/documents/'.$val);
                        array_push($rmimg,$image);


                        if($folderName != ''){
                            $path = public_path("/uploads/$userid/$folderName");
                            if (!File::isDirectory($path)) {
                                File::makeDirectory($path, 0777, true, true);
                            }
                            File::copy($image, public_path("/uploads/$userid/$folderName/".$val));
                            array_push($attachment_img,public_path("/uploads/$userid/$folderName/".$val));
                        }else{
                            $path = public_path("/uploads/$userid");
                            if (!File::isDirectory($path)) {
                                File::makeDirectory($path, 0777, true, true);
                            }
                            File::copy($image, public_path("/uploads/$userid/".$val));
                            array_push($attachment_img,public_path("/uploads/$userid/".$val));
                        }
                    }
                }
                File::delete($rmimg);
            }
        }

        $usersdata = array();
        if(isset($request->users) && count($request->users) > 0){
            $users = $request->users;
            $usersdata = User::whereIn('id',$request->users)->get();

        }
        $documents = new Document();
        $documents->project_id  = $request->project_id;
        $documents->folder      = $request->folder;
        $documents->users       = json_encode($users);
        $documents->created_by  = $userid;
        $documents->save();
        $attachments = json_encode($filesarr);

        $documents->title = $request->title;
        $documents->attachment = json_encode($filesarr);
        $documents->save();

        if(count($usersdata) > 0){
            $projectname = '';
            if($request->project_id != ''){
                $project = Project::where('id',$request->project_id)->first();
                $projectname = $project->name;
            }

            foreach($usersdata as $val){
                $firstname  = $val->first_name;
                $last_name  = $val->last_name;
                $email      = $val->email;
                Mail::to($email)->send(new DocumentsEmail($firstname,$last_name,$projectname,$request->title,$attachment_img));
            }
        }

        return redirect('/admin/document_storage')->with('message', 'Document created successfully!');
    }
    public function getAttachments($id){
        $docsattached = DocumentThreads::find($id);
        $images = array();
        if($docsattached && count(json_decode($docsattached->attachment)) > 0){
            $attachments = json_decode($docsattached->attachment);
            foreach($attachments as $val){
                $filesize = public_path().'/uploads/documents/'.$val;
                $file = url('/uploads/documents/'.$val);

                $imagedata = filesize($filesize);
                $data = array('name'=>$val,"fullimage"=>$file,'size'=>$imagedata);
                array_push($images,$data);
            }
        }
        return $images;
    }
    public function edit_document($id) {
        $documents = Document::with('projectName','folderData','DocumentThreads','DocumentThreads.threadProjectName','DocumentThreads.threadFolderData')->find($id);
        $companyid  = Auth::user()->company_id;
        $role       = Auth::user()->role;
        $userid     = Auth::id();
        if($role != 3){
            $list_projects = Project::where('company_id',$companyid)->orderby('id','DESC')->get();
        }else{

            $list_projects = Project::wherehas('projectUsers', function($q) use($userid){
                $q->where('user_id', $userid);
            })->orderby('id','DESC')->get();
        }
        $folders = DocumentsFolders::where('user_id',Auth::id())->get();
        $users_data = User::where('id', '!=', Auth::id())->where('company_id',$companyid)->get();
        //echo '<pre>'; print_r($documents->toArray());die;
        return view('admin.document.document_edit',compact('documents','list_projects','users_data','folders'));
   }
   public function update_document(Request $request){
        validator($request->all(), [
            'title' => 'required'
        ])->validate();
        $userid = Auth::id();
        $filesarr = $users = array();
        $attachment_img = $rmimg = array();
        $folderName = '';
        if($request->folder != ''){
            $doc_indb = DocumentsFolders::where('id',$request->folder)->first();
            if($doc_indb){
                $folderName = $doc_indb->folder;
            }
        }
        if($request->attachments != ''){
            $newfiles = explode(',',$request->attachments);
            if(count($newfiles) > 0){
                foreach($newfiles as $val){
                    if(file_exists( public_path().'/uploads/documents/'.$val)){
                        array_push($filesarr,$val);
                        $image = public_path('uploads/documents/'.$val);
                        array_push($rmimg,$image);
                        if($folderName != ''){
                            $path = public_path("/uploads/$userid/$folderName");
                            if (!File::isDirectory($path)) {
                                File::makeDirectory($path, 0777, true, true);
                            }
                            File::copy($image, public_path("/uploads/$userid/$folderName/".$val));
                            array_push($attachment_img,public_path("/uploads/$userid/$folderName/".$val));
                        }else{
                            $path = public_path("/uploads/$userid");
                            if (!File::isDirectory($path)) {
                                File::makeDirectory($path, 0777, true, true);
                            }
                            File::copy($image, public_path("/uploads/$userid/".$val));
                            array_push($attachment_img,public_path("/uploads/$userid/".$val));
                        }
                    }
                }
                File::delete($rmimg);
            }
        }
        $usersdata = array();
        if(isset($request->users) && count($request->users) > 0){
            $users = $request->users;
            $usersdata = User::whereIn('id',$request->users)->get();

        }
        $documents = new DocumentThreads();
        $documents->project_id  = $request->project_id;
        $documents->document_id = $request->id;
        $documents->folder      = $request->folder;
        $documents->users       = json_encode($users);
        $documents->uploaded_by	= $userid;
        $attachments = json_encode($filesarr);

        $documents->title = $request->title;
        $documents->attachment = json_encode($filesarr);
        $documents->save();

        if(count($usersdata) > 0){
            $projectname = '';
            if($request->project_id != ''){
                $project = Project::where('id',$request->project_id)->first();
                $projectname = $project->name;
            }

            foreach($usersdata as $val){
                $firstname  = $val->first_name;
                $last_name  = $val->last_name;
                $email      = $val->email;
                Mail::to($email)->send(new DocumentsEmail($firstname,$last_name,$projectname,$request->title,$attachment_img));
            }
        }

        return redirect('/admin/document_storage')->with('message', 'Document added successfully!');
    }
    public function detele_document($id) {
        Document::find($id)->delete();
        // $attachment = DocumentThreads::where('document_id',$id)->pluck('attachment');
        // if($attachment->count() > 0){
        //     $attc = json_decode($attachment->toArray()[0]);
        //     foreach($attc as $val){
        //         $filepath = public_path().'/uploads/documents/'.$val;
        //         if(\File::exists($filepath)){
        //             \File::delete($filepath);
        //         }
        //     }
        // }
        DocumentThreads::where('document_id',$id)->delete();
        return redirect()->back()->with('message', 'Record deleted successfully!');
   }
    public function download_document( $id = '' )
    {
        $document = DocumentThreads::with('threadFolderData')->find(base64_decode($id));
        //echo '<pre>';print_r($document);die;
        $docs = json_decode($document->attachment);
        $totalattachments = count($docs);
        if($totalattachments > 0){
            if($totalattachments == 1){
                $filename = $docs[0];
                $file_path = public_path('/uploads/documents/').$filename;

                $checkpatch = public_path('/uploads/').$val->uploaded_by.'/'.$filename;
                $folderimage = url('uploads/'.$val->uploaded_by.'/'.$filename);

            // echo $file_path;die;
                $headers = array(
                    'Content-Type: csv',
                    'Content-Disposition: attachment; filename='.$filename,
                );
                if ( file_exists( $file_path ) ) {
                    // Send Download
                    return \Response::download( $file_path, $filename, $headers );
                }else if(file_exists( $checkpatch)){
                    return \Response::download( $folderimage, $filename, $headers );
                } else {
                    // Error
                    exit( 'Requested file does not exist on our server!' );
                }
            }else{
                $filename = uniqid(time()) . '.zip';
                $zip = new ZipArchive();
                $zip->open($filename, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
                foreach($docs as $val){
                    if($document->threadFolderData){

                        $checkpatch = public_path('/uploads/').$document->threadFolderData->user_id.'/'.$document->threadFolderData->folder.'/'.$val;
                        $folderimage = url('uploads/'.$document->threadFolderData->user_id.'/'.$document->threadFolderData->folder.'/'.$val);
                    }else{
                        $checkpatch = public_path('/uploads/').$document->uploaded_by.'/'.$val;
                        $folderimage = url('uploads/'.$document->uploaded_by.'/'.$val);
                    }
                    $file_path = public_path('/uploads/documents/').$val;
                    if ( file_exists( $file_path ) ) {
                        $zip->addFile($file_path, $val);
                    }else if(file_exists( $checkpatch)){
                        $zip->addFile($checkpatch, $val);
                    }
                }
                $zip->close();
                return response()->download($filename);
            }

        }
    }
    public function download_doc( $id = '' )
    {
        $document = Document::with('folderData')->find(base64_decode($id));
        // echo '<pre>';print_r($document);die;
        $docs = json_decode($document->attachment);
        $totalattachments = count($docs);
        if($totalattachments > 0){
            if($totalattachments == 1){
                $filename = $docs[0];
                if($document->folderData){
                    $checkpatch = public_path('/uploads/').$document->folderData->user_id.'/'.$document->folderData->folder.'/'.$filename;
                }else{
                    $checkpatch = public_path('/uploads/').$document->created_by.'/'.$filename;
                }

                $file_path = public_path('/uploads/documents/').$filename;
            // echo $file_path;die;
                $headers = array(
                    'Content-Type: csv',
                    'Content-Disposition: attachment; filename='.$filename,
                );
                if ( file_exists( $file_path ) ) {
                    // Send Download
                    return \Response::download( $file_path, $filename, $headers );
                }else if(file_exists( $checkpatch)){
                    return \Response::download( $checkpatch, $filename, $headers );
                } else {
                    // Error
                    exit( 'Requested file does not exist on our server!' );
                }
            }else{
                $filename = uniqid(time()) . '.zip';
                $zip = new ZipArchive();
                $zip->open($filename, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
                foreach($docs as $val){
                    if($document->folderData){
                        $checkpatch = public_path('/uploads/').$document->folderData->user_id.'/'.$document->folderData->folder.'/'.$val;
                    }else{
                        $checkpatch = public_path('/uploads/').$document->created_by.'/'.$val;
                    }
                    $file_path = public_path('/uploads/documents/').$val;
                    if ( file_exists( $file_path ) ) {
                        $zip->addFile($file_path, $val);
                    }else if(file_exists( $checkpatch)){
                        $zip->addFile($checkpatch, $val);
                    }

                }
                $zip->close();
                return response()->download($filename);
            }
        }

    }

    public function add_folder(Request $request){
        $userid = Auth::id();
        $folderName = $request->foldername;
        $doc_indb = DocumentsFolders::where('user_id',$userid)->where('folder',$folderName)->first();
        if($doc_indb){
            return response()->json([
                'data' => $doc_indb,
                'status' => false,
                'message' => 'Folder already exists.'
            ]);
        }
        $path = public_path("uploads/$userid/$folderName");

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
        $folder = new DocumentsFolders();
        $folder->folder     = $request->foldername;
        $folder->user_id    = $userid;
        $folder->save();

        return response()->json([
            'data' => $folder,
            'status' => true,
            'message' => 'Folder Created successfully'
        ]);

    }

    public function check_folder_exists(Request $request){
        $userid = Auth::id();
        $folderName = $request->foldername;
        $doc_indb = DocumentsFolders::where('user_id',$userid)->where('folder',$folderName)->first();
        if($doc_indb){
            return response()->json([
                'data' => $doc_indb,
                'status' => false,
                'message' => 'Folder already exists.'
            ]);
        }
        $path = public_path("uploads/$userid/$folderName");

        return response()->json([
            'data' => array(),
            'status' => true,
            'message' => ''
        ]);
    }
}
