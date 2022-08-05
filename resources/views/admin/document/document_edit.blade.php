@extends('layouts.admin')
@section('content')
</head>
<body>
<div id="page-wrapper">

    <div class="header">
    </div>
    <div id="page-inner">
        <input type='hidden' value="{{url('admin/attachment_add')}}" id = 'formuploadurl'/>
        <input type='hidden' value="{{url('admin/attachment_rm')}}" id = 'rmurl'/>
        <input type='hidden' value="{{url('admin/getAttachments',@$documents->documentAttachments[0]->id)}}" id = 'getAttachments'/>
        <input type='hidden' value="{{csrf_token()}}" id = 'csrfToken'/>


        <div class="btn_form" ><a href="{{ url()->previous() }}">
            <button  class="btn btn-primary float-right">Back</button></a>
        </div>

        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <table  id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">Sr no.</th>
                    <th scope="col">Title</th>
                    <th scope="col">Project</th>
                    <th scope="col">Uploaded By</th>
                    <th scope="col">Date</th>
                    <th scope="col">Documents</th>
                    <th scope="col">Document Url</th>
                </tr>
            </thead>
            <tbody>

                @if($documents)
                    <?php $i=1;
                    $docs = json_decode($documents->attachment);
                    ?>
                    <tr>
                        <th scope="col">{{@$i}}</th>
                        <th scope="col">{!!@$documents->title!!}</th>
                        <th scope="col">{{@$documents->projectName->name}}</th>
                        <th scope="col">{{@$documents->user->first_name}} {{@$documents->user->last_name}}</th>
                        <th scope="col">{{@$documents->created_at}}</th>
                        <th scope="col">
                            <?php
                            if(count($docs) > 0){
                                foreach($docs as $val){
                                    if($documents->folderData){
                                        $checkpatch = public_path('/uploads/').$documents->folderData->user_id.'/'.$documents->folderData->folder.'/'.$val;
                                        $folderimage = url('uploads/'.$documents->folderData->user_id.'/'.$documents->folderData->folder.'/'.$val);
                                    }else{
                                        $checkpatch = public_path('/uploads/').$documents->created_by.'/'.$val;
                                        $folderimage = url('uploads/'.$documents->created_by.'/'.$val);
                                    }
                                    $image = url('uploads/documents/'.$val);
                                    $imgarr = array('jpg','jpeg','jpe','jif','jfif','jfi','png','gif','webp','tiff','tif','bmp','dib');
                                    $ext = pathinfo($image);

                                    if(file_exists( $image)){
                                        if(in_array($ext['extension'],$imgarr)){
                                    ?>
                                            <a class="thumbnail fancybox customwidthheights1" href="<?php echo $image; ?>" data-fancybox-group="attachment">
                                                <img src='<?php echo $image; ?>' />
                                            </a>
                                        <?php
                                        }else if($ext['extension'] == 'pdf'){
                                            echo '<img src="'.@url('/images/pdf.png').'" style="width:200px;"/>';
                                        }else{
                                            echo '<img src="'.@url('/images/docs.png').'" style="width:200px;"/>';
                                        }
                                    }else if(file_exists( $checkpatch)){
                                        $folderext = pathinfo($folderimage);
                                        if(in_array($folderext['extension'],$imgarr)){
                                    ?>
                                            <a class="thumbnail fancybox customwidthheights1" href="<?php echo $folderimage; ?>" data-fancybox-group="attachment">
                                                <img src='<?php echo $folderimage; ?>' />
                                            </a>
                                        <?php
                                         }else if($ext['extension'] == 'pdf'){
                                            echo '<img src="'.@url('/images/pdf.png').'" style="width:200px;"/>';
                                        }else{
                                            echo '<img src="'.@url('/images/docs.png').'" style="width:200px;"/>';
                                        }
                                    }
                                }
                            }
                            ?>
                        </th>
                        <th scope="col">
                            <?php
                            if(count($docs) > 0){
                            ?>
                                <a href="javascript:void(0);" onclick="copyToClipboard('#textid{{@$documents->id}}')" >
                                    <i class="fa fa-copy" aria-hidden="true"></i>
                                </a>
                                <input type='hidden' id="textid{{@$documents->id}}" value="{{url('admin/download_doc')}}/<?php echo base64_encode(@$documents->id); ?>" />
                            <?php
                            }
                            ?>
                        </th>
                    </tr>
                    @if($documents->documentThreads)
                    <?php
                    $j=1;
                    $j = $i+$j;
                    ?>
                        @foreach($documents->documentThreads as $val)
                            <?php
                            if(isset($_GET['page'])){
                                if($_GET['page'] != 1){
                                    $j = ($_GET['page'] * 10) + 1;
                                }
                            }
                            $docs = json_decode($val->attachment);?>
                            <tr>
                                <th scope="col">{{@$j}}</th>
                                <th scope="col">{!!@$val->title!!}</th>
                                <th scope="col">{{@$val->threadProjectName->name}}</th>
                                <th scope="col">{{@$val->user->first_name}} {{@$val->user->last_name}}</th>
                                <th scope="col">{{@$val->created_at}}</th>
                                <th scope="col">
                                    <?php
                                    if(count($docs) > 0){
                                        foreach($docs as $vals){
                                            if($val->threadFolderData){
                                                $checkpatch = public_path('/uploads/').$val->threadFolderData->user_id.'/'.$val->threadFolderData->folder.'/'.$vals;
                                                $folderimage = url('uploads/'.$val->threadFolderData->user_id.'/'.$val->threadFolderData->folder.'/'.$vals);
                                            }else{
                                                $checkpatch = public_path('/uploads/').$val->uploaded_by.'/'.$vals;
                                                $folderimage = url('uploads/'.$val->uploaded_by.'/'.$vals);
                                            }
                                            $image = url('uploads/documents/'.$vals);
                                            $imgarr = array('jpg','jpeg','jpe','jif','jfif','jfi','png','gif','webp','tiff','tif','bmp','dib');
                                            $ext = pathinfo($image);
                                            if(file_exists( $image)){
                                                if(in_array($ext['extension'],$imgarr)){
                                            ?>
                                                    <a class="thumbnail fancybox customwidthheights1" href="<?php echo $image; ?>" data-fancybox-group="attachment">
                                                        <img src='<?php echo $image; ?>' />
                                                    </a>
                                                <?php
                                                }else if($ext['extension'] == 'pdf'){
                                                    echo '<img src="'.@url('/images/pdf.png').'" style="width:200px;"/>';
                                                }else{
                                                    echo '<img src="'.@url('/images/docs.png').'" style="width:200px;"/>';
                                                }
                                            }else if(file_exists( $checkpatch)){
                                                $folderext = pathinfo($folderimage);
                                                if(in_array($folderext['extension'],$imgarr)){
                                            ?>
                                                    <a class="thumbnail fancybox customwidthheights1" href="<?php echo $folderimage; ?>" data-fancybox-group="attachment">
                                                        <img src='<?php echo $folderimage; ?>' />
                                                    </a>
                                                <?php
                                                }else if($folderext['extension'] == 'pdf'){
                                                    echo '<img src="'.@url('/images/pdf.png').'" style="width:200px;"/>';
                                                }else{
                                                    echo '<img src="'.@url('/images/docs.png').'" style="width:200px;"/>';
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </th>
                                <th scope="col">
                                    <?php
                                    if(count($docs) > 0){
                                    ?>
                                        <a href="javascript:void(0);" onclick="copyToClipboard('#threadTextid{{@$val->id}}')" >
                                            <i class="fa fa-copy" aria-hidden="true"></i>
                                        </a>
                                        <input type='hidden' id="threadTextid{{@$val->id}}" value="{{url('admin/download_document')}}/<?php echo base64_encode(@$val->id); ?>" />
                                    <?php
                                    }
                                    ?>
                                </th>
                            </tr>
                            <?php $j++; ?>
                        @endforeach
                    @endif
                @else
                    <tr><td colspan='7'>No Document found</td></tr>
                @endif
            </tbody>
        </table>
        <div class="testbox">
            <form method="POST" action="{{url('admin/update_document')}}" enctype="multipart/form-data">
                @csrf
                <input type='hidden' name='id' value='{{ @$documents->id }}' />
                <div class="company_info">
                    <div class="row">
                    </div>
                    <label class="font-weight-bold">Enter Document details below:</label>
                </div>

                <div class="colums project">
                    <div class="item">
                        <select name="folder" class='selectfolder' >
                            <option value="">Select Folder</option>
                            @if(count($folders) > 0)
                                @foreach($folders as $val)
                                    <option value='{{ $val->id }}'>{{ $val->folder }}</option>
                                @endforeach
                            @endif
                            <option value="addnew">Add New</option>
                        </select>
                    </div>
                    <div class="item">
                        <select name="project_id" class='selectproject' >
                            <option value="">Select Project</option>
                            @if(count($list_projects) > 0)
                                @foreach($list_projects as $val)
                                    <option value='{{ $val->id }}'>{{ $val->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="item" style='width: 100% !important;'>
                    <?php
                    $usersdata = json_decode($documents->users);
                    ?>
                        <select name="users[]" class='selectto' multiple="multiple" >
                            @if(count($users_data) > 0)
                                @foreach($users_data as $val)
                                    <option value='{{ $val->id }}' >{{ $val->first_name }} {{ $val->last_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                    <div class="item1">
                    <textarea id="w3review" class="ckeditor" name="title" rows="4" cols="50" placeholder="Enter Document Details" required >{{@$documents->documentAttachments[0]->title}}</textarea></div>
                    <div class="dropzone" id="myDropzone" style="width:100%;">
                </div>
                &nbsp;
                <div class="btn-block">
                    <input type='hidden' id='dropzoneimage' name='attachments' />
                    <button type="submit" href="/">Create</button>
                </div>
            </form>
        </div>
    </div>
    <input type='hidden' value="{{ route('documents.add-folder') }}" id='add-folder' />
    <input type='hidden' value="{{ route('documents.check-folder') }}" id='check-folder' />
    <input type='hidden' value="{{ csrf_token() }}" id='csrfToken' />
    <div class="modal fade" id="modeladdfolder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <form action="{{url('admin/add_folder')}}" method="POST" class='popuproleform addfolder'>
            @csrf
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLongTitle">Folders</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="folderName">Folder Name</label>
                        <input type="text" value="" id='folderName' class="form-control" name="folder" placeholder="Enter Folder Name" required>
                        <div class="append_error alert alert-warning" style='display:none;' role="alert">Folder Already exists.</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" value="Add" class="btn btn-primary" style='width:auto;'>
                    </div>
                    <div class="form-group">

                    </div>
                </div>

            </div>
            </div>
        </form>
    </div>

    <script src="{{ asset('ckeditor/ckeditor.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
    });
</script>
@endsection



