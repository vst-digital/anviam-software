@extends('layouts.admin')
@section('content')
<div id="page-wrapper">

<div class="header">
</div>
<div id="page-inner">

  </head>
  <body>

          <div class="btn_form" ><a href="{{ url()->previous() }}"><button  class="btn btn-primary float-right">Back</button></a>
         </div>

           @if(session()->has('message'))
                <div class="alert alert-success">
                {{ session()->get('message') }}
                </div>
            @endif

    <div class="testbox">
        <form method="POST" action="{{url('admin/update_issue')}}" enctype="multipart/form-data">
            @csrf
            <input type='hidden' value='<?php echo $memo->id; ?>' name='id' />
            <div class="company_info">
                <label class="font-weight-bold">Enter Memo details below:</label>
            </div>

            <div class="colums project">
                <div class="item">
                    <select name="project_id" required class='selectproject form-control' >
                        @if(count($list_projects) > 0)
                            @foreach($list_projects as $val)
                                <option value='{{ $val->id }}' <?php if($val->id == $memo->project_id){ echo "Selected"; } ?>>{{ $val->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <?php
                if($memo->response != '' && $memo->response != 'null'){
                    $response_required = json_decode($memo->response);
                }else{
                    $response_required = array();
                }
                ?>
                <div class="item">
                    <input type="text" name="project_number" required placeholder="Project Number" value="{{ $memo->project_number }}"/>
                </div>
                <div class="item">
                    <input type="text" name="correspondence_no" required placeholder="Correspondence Number" value="{{ $memo->correspondence_no }}"/>
                </div>
                <div class="item">
                    <input type="text" name="datetime" required placeholder="Date Time" class="datetimepicker" value="{{ $memo->datetime }}"/>
                    <!-- <input type="text" name="time" required placeholder="Time" id="timepicker" class='col-md-5' style='float:right;'/> -->
                </div>
                <div class="item">
                    <!-- <input type="text" name="name" required placeholder="Name" /> -->
                    <select name="users[]" required class='selectto' multiple="multiple" >
                        @if(count($users_data) > 0)
                            @foreach($users_data as $val)
                                <option <?php if(in_array($val->id,$memousers)){ echo 'selected'; } ?> value='{{ $val->id }}'>{{ $val->first_name }} {{ $val->last_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="item">
                    <input type="text" name="subject" required placeholder="Subject" value="{{ $memo->subject }}"/>
                </div>
                <div class="item columnlabel">
                    <label class="form-check-label checkboxlabelcls col-md-12" >Response required</label>
                    <div class="col-sm-2">
                        <input name='response[]' class="form-check-input checkboxcls" type="checkbox" value="1" id = '1day' <?php if(in_array('1',$response_required)){ echo 'checked'; } ?>>
                        <label class="form-check-label checkboxlabelcls" for="1day">1 Day</label>
                    </div>
                    <div class="col-sm-2">
                        <input name='response[]' class="form-check-input checkboxcls" type="checkbox" value="2" id="2day" <?php if(in_array('2',$response_required)){ echo 'checked'; } ?>>
                        <label class="form-check-label checkboxlabelcls" for="2day">2 Days</label>
                    </div>
                    <div class="col-sm-2">
                        <input name='response[]' <?php if(in_array('7',$response_required)){ echo 'checked'; } ?> class="form-check-input checkboxcls" type="checkbox" value="7" id="7day">
                        <label class="form-check-label checkboxlabelcls" for="7day">7 Days</label>
                    </div>
                </div>
                <?php
                $attachment = $tag = array();
                $attachment = json_decode($memo->attachment);
                $tag        = json_decode($memo->tag);
                ?>
                <div class="item attach_tag">
                    <div class="col-sm-6">
                        <label class="form-check-label col-md-12 p-0">Attachment</label>
                        <input type='file' name="attachment[]" placeholder="attachment" multiple/>
                        @if($attachment)
                            @foreach($attachment as $val)
                            <span style='float:left;width:100px'>
                                <a href='{{ @url("/uploads/memo/attachment/$val") }}' data-fancybox-group="attachment" class='fancybox'>
                                    <img src='{{ @url("/uploads/memo/attachment/$val") }}' width='100%'/>
                                </a>
                                <!-- <a href="javascript:void(0);">Remove</a> -->
                            </span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col-sm-6">
                        <label class="form-check-label col-md-12" >Tag</label>
                        <input type='file' name="tag[]" placeholder="tag" multiple/>
                        @if($tag)
                            @foreach($tag as $val)
                            <span style='float:left;width:100px'>
                                <a href='{{ @url("/uploads/memo/tag/$val") }}' data-fancybox-group="tag" class='fancybox'>
                                    <img src='{{ @url("/uploads/memo/tag/$val") }}' width='100%'/>
                                </a>
                                <!-- <a>Remove</a> -->
                            </span>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="item">
                    <input name="location" placeholder="Location" value="{{ $memo->location }}" required/>
                </div>
                <div class="item">
                    <textarea id="w3review" name="memo" rows="4" cols="50" placeholder="Enter Memo" required>{{ $memo->memo }}</textarea>
                </div>

            </div>
            &nbsp;
            <div class="btn-block">
                <button type="submit" >Update</button>
            </div>
        </form>
    </div>
</div>

@endsection
