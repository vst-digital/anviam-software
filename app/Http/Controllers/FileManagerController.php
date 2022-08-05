<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Models\User;
use App\Models\FileManagerFolder;
use App\Models\AclFiles;
use App\Models\ShareFile;
use App\Mail\ShareEmil;
use File;
use Mail;
use Alexusmai\LaravelFileManager\Events\BeforeInitialization;
use Alexusmai\LaravelFileManager\Events\Deleting;
use Alexusmai\LaravelFileManager\Events\DirectoryCreated;
use Alexusmai\LaravelFileManager\Events\DirectoryCreating;
use Alexusmai\LaravelFileManager\Events\DiskSelected;
use Alexusmai\LaravelFileManager\Events\Download;
use Alexusmai\LaravelFileManager\Events\FileCreated;
use Alexusmai\LaravelFileManager\Events\FileCreating;
use Alexusmai\LaravelFileManager\Events\FilesUploaded;
use Alexusmai\LaravelFileManager\Events\FilesUploading;
use Alexusmai\LaravelFileManager\Events\FileUpdate;
use Alexusmai\LaravelFileManager\Events\Paste;
use Alexusmai\LaravelFileManager\Events\Rename;
use Alexusmai\LaravelFileManager\Events\Zip as ZipEvent;
use Alexusmai\LaravelFileManager\Events\Unzip as UnzipEvent;
use Alexusmai\LaravelFileManager\Requests\RequestValidator;
use Alexusmai\LaravelFileManager\FileManager;
use Alexusmai\LaravelFileManager\Services\Zip;
use Illuminate\Routing\Controller;

class FileManagerController extends Controller
{

    //
        /**
     * @var FileManager
     */
    public $fm;

    /**
     * FileManagerController constructor.
     *
     * @param FileManager $fm
     */
    public function __construct(FileManager $fm)
    {
        $this->fm = $fm;
    }

    /**
     * Initialize file manager
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function initialize()
    {
        event(new BeforeInitialization());

        return response()->json(
            $this->fm->initialize()
        );
    }

    public function index(Request $request)
    {
       return view('admin/filemanager');
    }

    public function content(RequestValidator $request)
    {
        return response()->json(
            $this->fm->content(
                $request->input('disk'),
                $request->input('path')
            )
        );
    }

    public function upload(RequestValidator $request)
    {

        event(new FilesUploading($request));

        $uploadResponse = $this->fm->upload(
            $request->input('disk'),
            $request->input('path'),
            $request->file('files'),
            $request->input('overwrite')
        );
        $directory = $request->input('path');
        // $getdirectoryId ="";
        $userid = Auth::user()->id;
        if($request->input('disk')!="" && $directory==""){
            $getdirectoryId = FileManagerFolder::where('disk',$request->input('disk'))
                            ->where('user_id',$userid)
                            ->first();
        }
        if($request->input('disk')!="" && $directory!=""){
            $getdirectoryId = FileManagerFolder::where('disk',$request->input('disk'))
                              ->where('path',$directory)
                              ->where('user_id',$userid)
                              ->first();
        }

        $path = $request->path();
        $full_path = $path.'/'.$directory;
        // $userid = Auth::user()->id;
        $files =  $request->file('files');
        $directoryid = 0;
        if($getdirectoryId){
            $getdirectoryId = $getdirectoryId->id;
        }
        foreach($files as $file){
             if ($file) {
                $image = $file;
                $name = $file->getClientOriginalName();
                $Fileupload = new AclFiles();
                $Fileupload->acl_rules = $getdirectoryId;
                $Fileupload->file  = $name;
                $Fileupload->save();
            }
        }
        event(new FilesUploaded($request));
        \Event::listen('Alexusmai\LaravelFileManager\Events\FilesUploading',
            function ($event) {
               //echo '<pre>';print_r($event);
            }
        );

        return response()->json($uploadResponse);
    }



    /**
     * Create new directory
     *
     * @param RequestValidator $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createDirectory(RequestValidator $request)
    {
        event(new DirectoryCreating($request));

        $createDirectoryResponse = $this->fm->createDirectory(
            $request->input('disk'),
            $request->input('path'),
            $request->input('name')
        );

        if ($createDirectoryResponse['result']['status'] === 'success') {
            event(new DirectoryCreated($request));
        }

        $userid = Auth::user()->id;
        $path = public_path("/uploads/$userid");
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
        if($request->input('path')!=""){
           $path = $request->input('path').'/'.$request->input('name');
        }else{
            $path = $request->input('name');
        }
        $Fileupload = new FileManagerFolder();
        $Fileupload->user_id = $userid;
        $Fileupload->disk = $request->input('disk');
        $Fileupload->path = $path;
        $Fileupload->save();

        return response()->json($createDirectoryResponse);
    }


    public function filefolderpath(Request $request) {
        $user_id = Auth::user()->id;
        $fileinfo = $request->filepath;
        $info = pathinfo($fileinfo);
        $file_name =  basename($fileinfo);
        $ext = explode('/', $fileinfo);
        $arrindex = array_search($user_id,$ext);
        $slicedarr = array_slice($ext,$arrindex);
        array_shift($slicedarr);
        $filedid = 0;
        $mediaid = 0;
        if(count($slicedarr) >= 1){
            array_pop($slicedarr);
            $folder = implode('/', $slicedarr);

            $getdirectoryId = FileManagerFolder::where('path',$folder)
                              ->where('user_id',$user_id)
                              ->first();
            if($getdirectoryId){
                $filedid = $getdirectoryId->id;
                $getmediaId = AclFiles::where('file',$file_name)
                                    ->where('acl_rules',$filedid)
                                    ->first();
                if($getmediaId){
                    $mediaid = $getmediaId->id;
                }
            }else{
                $getmediaId = AclFiles::where('file',$file_name)
                                    ->first();
                if($getmediaId){
                    $mediaid = $getmediaId->id;
                }
            }
        }
        if($filedid != 0){

        }
        $users = $request->userid;
        if(count($users) > 0){
            foreach($users as $val){
                $FilePath = new ShareFile;
                $FilePath->folder_id = $filedid;
                $FilePath->file_id = $mediaid;
                $FilePath->shared_by = $user_id;
                $FilePath->shared_to = $val;
                $FilePath->save();
            }
        }

        $users = User::where('id', $FilePath->shared_to)->first();
        if($FilePath->shared_to){
            Mail::to($users->email)->send(new ShareEmil($fileinfo,$FilePath->id));
        }
        return true;

    }

    public function sharefile(Request $request)
    {
        $ShareFile = ShareFile::find($request->id);
        $file = $folder = '';
        if($ShareFile->folder_id != '' && $ShareFile->folder_id != 0){
            $folderpath = FileManagerFolder::find($ShareFile->folder_id);
            if($folderpath){
                $folder = '/'.$folderpath->path;
            }
        }
        if($ShareFile->file_id != '' && $ShareFile->file_id != 0){
            $filepath = AclFiles::find($ShareFile->file_id);
            if($filepath){
                $file = $filepath->file;
            }
        }
        if($folder == ""){
            $filepath = url('/').'/uploads/'.$ShareFile->shared_by.'/'.$file;
        }else{
            $filepath = url('/').'/uploads/'.$ShareFile->shared_by.$folder.'/'.$file;
        }
        // $filepath = url('/').'/uploads/'.$ShareFile->shared_by.$folder.'/'.$file;

        
        return view('share/download_file',compact('filepath'));
    }


}
