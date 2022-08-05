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
        <input type='hidden' value="{{csrf_token()}}" id = 'csrfToken'/>


        <div class="btn_form" ><a href="{{ url()->previous() }}">
            <button  class="btn btn-primary float-right">Back</button></a>
        </div>

        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif

        <div class="testbox">
            <form method="POST" action="{{url('admin/save_document')}}" enctype="multipart/form-data">
                @csrf
                <div class="company_info">
                    <div class="row">
                    </div>
                    <label class="font-weight-bold">Enter Transmittals details below:</label>
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
                            <option value="addnew" class="add-newbtn">Add New</option>
                        </select>
                    </div>
                    <div class="item customselectwidth">
                        <select name="project_id" class='selectproject' >
                            <option value="">Select Project</option>
                            @if(count($list_projects) > 0)
                                @foreach($list_projects as $val)
                                    <option value='{{ $val->id }}'>{{ $val->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="item customselectwidth" style='width: 100% !important;'>
                        <select name="users[]" class='selectto' multiple="multiple" >
                            @if(count($users_data) > 0)
                                @foreach($users_data as $val)
                                    <option value='{{ $val->id }}'>{{ $val->first_name }} {{ $val->last_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                    <div class="item1">
                        <textarea id="w3review" class="ckeditor" name="title" rows="4" cols="50" placeholder="Enter Document Details" required></textarea>
                    </div>
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

<!-- <script src="{{ asset('ckeditor/ckeditor.js')}}"></script> -->

<script type="text/javascript">
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
    });
</script>
<style type="text/css">
    li#select2-folder-z6-result-nvc9-addnew {
    background: red;
}
</style>

@endsection



