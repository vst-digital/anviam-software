@extends('layouts.admin')
@section('content')
    <div id="page-wrapper">

        <div class="header">
        </div>

        <div id="page-inner">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="page-header">Memo Storage List</h2>
                </div>
            </div>

        @if(session()->has('message'))
            <div class="alert alert-success">
            {{ session()->get('message') }}
            </div>
        @endif
        <?php
        $memo_id = '';
        if(isset($_GET['memo_id'])){
            $memo_id = $_GET['memo_id'];
        }
        ?>
        <div class="searchforms">
            <form action='' >
                <div class='col-md-4 pl-0'>
                    <select name="memo_id" required class='form-control' >
                        <option value=''> Select Group </option>
                        @if(count($memos) > 0)
                            @foreach($memos as $val)
                                <option <?php if($memo_id == $val->id){ echo 'selected'; } ?> value='{{ $val->id }}'>{{ $val->project_number }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class='col-md-2 pl-0'>
                    <input type='submit' class='btn btn-primary' value='Search'/>
                </div>
                <div class='col-md-2 pl-0'>
                    <a href="{{URL::to('admin/memo_storage')}}" class='btn btn-primary'/>Reset</a>
                </div>
            </form>
        </div>
        <!-- Gallery -->
        <div class="col-md-12 storage_list">
            @if(count($memo) > 0)
                @foreach($memo as $vals)
                    @if(isset($vals->attachment) && $vals->attachment != '')
                        <?php
                            $attachmentthread = array();
                            $attachmentthread = json_decode($vals->attachment);
                        ?>
                        @if($attachmentthread)
                            @foreach($attachmentthread as $val)
                                <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                    <a class="thumbnail fancybox customwidthheight" href='{{ @url("/uploads/memo/attachment/$val") }}' data-fancybox-group="attachment">
                                        <img class="img-thumbnail" src='{{ @url("/uploads/memo/attachment/$val") }}' style="width:100%; height:100%">
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    @endif
                    @if(isset($vals->tag) && $vals->tag != '')
                        <?php
                            $tag = array();
                            $tagthread        = json_decode($vals->tag);
                        ?>
                        @if($tagthread)
                            @foreach($tagthread as $val)
                                <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                    <a class="thumbnail fancybox customwidthheight" href="{{ @url("/uploads/memo/tag/$val") }}" data-fancybox-group="attachment">
                                        <img class="img-thumbnail" src="{{ @url("/uploads/memo/tag/$val") }}" style="width:100%; height:100%">
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    @endif
                    @if(@$vals->memoThreads)
                        @foreach($vals->memoThreads as $value)
                            @if($value->attachment != '')
                                <?php
                                    $attachmentthread1 = array();
                                    $attachmentthread1 = json_decode($value->attachment);
                                ?>
                                @if($attachmentthread1)
                                    @foreach($attachmentthread1 as $val)
                                        <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                            <a class="thumbnail fancybox customwidthheight" href='{{ @url("/uploads/memo/attachment/$val") }}' data-fancybox-group="attachment">
                                                <img class="img-thumbnail" src='{{ @url("/uploads/memo/attachment/$val") }}' style="width:100%; height:100%">
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                            @endif
                            @if($value->tag != '')
                                <?php
                                $tagthread1  = array();
                                $tagthread1  = json_decode($value->tag);
                                ?>
                                @if($tagthread1)
                                    @foreach($tagthread1 as $val)
                                        <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                            <a class="thumbnail fancybox customwidthheight" href="{{ @url("/uploads/memo/tag/$val") }}" data-fancybox-group="attachment">
                                                <img class="img-thumbnail" src="{{ @url("/uploads/memo/tag/$val") }}" style="width:100%; height:100%">
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endif

        </div>
        <!-- Gallery -->
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
