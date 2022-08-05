@extends('layouts.admin')
@section('content')
    <div id="page-wrapper">

        <div class="header">
        </div>

        <div id="page-inner">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="page-header">Chat Storage List</h2>
                </div>
            </div>

        @if(session()->has('message'))
            <div class="alert alert-success">
            {{ session()->get('message') }}
            </div>
        @endif
        <?php
        $user_id = $search = '';
        if(isset($_GET['user_id'])){
            $user_id = $_GET['user_id'];
        }
        ?>
        <div class="searchforms">
            <form action='' >
                <div class='col-md-4 pl-0'>
                    <select name="user_id" required class='form-control' >
                        <option value=''> Select User </option>
                        @if(count($users) > 0)
                            @foreach($users as $val)
                                <option <?php if($user_id == $val->id){ echo 'selected'; } ?> value='{{ $val->id }}'>{{ $val->first_name }} {{ $val->last_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class='col-md-2 pl-0'>
                    <input type='submit' class='btn btn-primary' value='Search'/>
                </div>
                <div class='col-md-2 pl-0'>
                    <a href="{{URL::to('admin/storage')}}" class='btn btn-primary'/>Reset</a>
                </div>
            </form>
        </div>
        <!-- Gallery -->
        <div class="col-md-12 storage_list">
            @if(count($chatimages) > 0)
                @foreach($chatimages as $val)
                    @if(isset($val->message->type) && $val->message->type == 2)
                        <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                            <a class="thumbnail fancybox customwidthheight" href="{{ $val->message->message}}" data-fancybox-group="attachment">
                                <img class="img-thumbnail" src="{{ $val->message->message}}" >
                            </a>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="col-lg-12 col-md-12 col-xs-12 thumb">
                    <h2>No images found!</h2>
                </div>
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
