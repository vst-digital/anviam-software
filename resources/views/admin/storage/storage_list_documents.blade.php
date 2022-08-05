@extends('layouts.admin')
@section('content')
    <div id="page-wrapper">

        <div class="header">
        </div>

        <div id="page-inner">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="page-header">Storage List</h2>
                </div>
            </div>

        @if(session()->has('message'))
            <div class="alert alert-success">
            {{ session()->get('message') }}
            </div>
        @endif
        <?php
        $document_id = $folder = '';
        if(isset($_GET['document_id'])){
            $document_id = $_GET['document_id'];
        }
        if(isset($_GET['folder'])){
            $folder = $_GET['folder'];
        }
        ?>
        <div class="searchforms">
            <form action='' >
                <div class='col-md-4 pl-0'>
                    <select name="document_id" class='form-control' >
                        <option value=''> Select Document </option>
                        @if(count($alldocuments) > 0)
                            @foreach($alldocuments as $val)
                                <option <?php if($document_id == $val->id){ echo 'selected'; } ?> value='{{ $val->id }}'>{{ $val->title }}</option>
                            @endforeach
                        @else
                            <option>No Project</option>
                        @endif
                    </select>
                </div>
                <div class='col-md-4 pl-0'>
                    <select name="folder" class='form-control' >
                        <option value="">Select Folder</option>
                        @if(count($folders) > 0)
                            @foreach($folders as $val)
                                <option <?php if($folder == $val->id){ echo 'selected'; } ?> value='{{ $val->id }}'>{{ $val->folder }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class='col-md-2 pl-0'>
                    <input type='submit' class='btn btn-primary' value='Search'/>
                </div>
                <div class='col-md-2 pl-0'>
                    <a href="{{URL::to('admin/storage_list_documents')}}" class='btn btn-primary'/>Reset</a>
                </div>
            </form>
        </div>
        <!-- Gallery -->
        <div class="col-md-12 storage_list">
            @if(count($documents) > 0)
                @foreach($documents as $vals)
                    @if(isset($vals->attachment) && $vals->attachment != '')
                        <?php
                            $attachmentthread = array();
                            $attachmentthread = json_decode($vals->attachment);
                        ?>
                        @if($attachmentthread)
                            @foreach($attachmentthread as $val)
                                <?php
                                if($vals->folderData){
                                    $checkpatch = public_path('/uploads/').$vals->folderData->user_id.'/'.$vals->folderData->folder.'/'.$val;
                                    $folderimage = url('uploads/'.$vals->folderData->user_id.'/'.$vals->folderData->folder.'/'.$val);
                                }else{
                                    $checkpatch = public_path('/uploads/').$vals->created_by.'/'.$val;
                                    $folderimage = url('uploads/'.$vals->created_by.'/'.$val);
                                }
                                $image = url('uploads/documents/'.$val);
                                if(file_exists( $image)){
                                ?>
                                    <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                        <a class="thumbnail fancybox customwidthheight" href='<?php echo $image; ?>' data-fancybox-group="attachment">
                                            <img class="img-thumbnail" src='<?php echo $image; ?>' style="width:100%; height:100%">
                                        </a>
                                    </div>
                                    <?php
                                }else if(file_exists( $checkpatch)){
                                ?>
                                    <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                        <a class="thumbnail fancybox customwidthheight" href='<?php echo $folderimage; ?>' data-fancybox-group="attachment">
                                            <img class="img-thumbnail" src='<?php echo $folderimage; ?>' style="width:100%; height:100%">
                                        </a>
                                    </div>
                                <?php
                                }
                                ?>
                            @endforeach
                        @endif
                    @endif
                    @if(@$vals->DocumentThreads)
                        @foreach($vals->DocumentThreads as $value)
                            @if($value->attachment != '')
                                <?php
                                    $attachmentthread1 = array();
                                    $attachmentthread1 = json_decode($value->attachment);
                                ?>
                                @if($attachmentthread1)
                                    @foreach($attachmentthread1 as $val)


                                        <?php
                                        if($value->threadFolderData){
                                            $checkpatch = public_path('/uploads/').$value->threadFolderData->user_id.'/'.$value->threadFolderData->folder.'/'.$val;
                                            $folderimage = url('uploads/'.$value->threadFolderData->user_id.'/'.$value->threadFolderData->folder.'/'.$val);
                                        }else{
                                            $checkpatch = public_path('/uploads/').$value->created_by.'/'.$val;
                                            $folderimage = url('uploads/'.$value->created_by.'/'.$val);
                                        }
                                        $image = url('uploads/documents/'.$val);
                                        if(file_exists( $image)){
                                        ?>
                                            <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                                <a class="thumbnail fancybox customwidthheight" href='<?php echo $image; ?>' data-fancybox-group="attachment">
                                                    <img class="img-thumbnail" src='<?php echo $image; ?>' style="width:100%; height:100%">
                                                </a>
                                            </div>
                                            <?php
                                        }else if(file_exists( $checkpatch)){
                                        ?>
                                            <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                                <a class="thumbnail fancybox customwidthheight" href='<?php echo $folderimage; ?>' data-fancybox-group="attachment">
                                                    <img class="img-thumbnail" src='<?php echo $folderimage; ?>' style="width:100%; height:100%">
                                                </a>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    @endforeach
                                @endif
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endif

        </div>
        <style type="text/css">
            .btn_form {
                padding: 3.5% 1%;
            }
            button.btn.btn-primary.float-right {
                float: right;
                margin: 0 2px;
            }
        </style>
@endsection
