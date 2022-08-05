@extends('layouts.admin')
@section('content')
<div id="page-wrapper">
<div class="header"></div>
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
         <form method="POST" action="{{url('admin/update_project')}}"  enctype="multipart/form-data">
            @csrf
            <div class="company_info">
               <div class="row">
               </div>
               <label class="font-weight-bold">Enter project details below:</label>
            </div>
            <div class="colums project">
                <div class="item">
                    <input type="text" name="name" required value="{{$data->name}}"
                    @if(auth()->user()->role==3) disabled @endif ></div>
                <div class="item">
                   <!--  <input type="text" name="name" required value="{{$data->name}}"
                    @if(auth()->user()->role==3) disabled @endif > -->
                    <select name="users[]" required class='selectuser' multiple="multiple" @if(auth()->user()->role==3) disabled @endif>
                        @foreach($users_data as $val)
                            <option <?php if(in_array($val->id, @$projectusers)){ echo 'selected'; }?> value='{{ $val->id }}'>{{ $val->first_name }} {{ $val->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="item caledndericon">
                    <input type="text" name="start_date" id="datetimepicker6" placeholder="Start Date" autocomplete="off" value="{{ $data->start_date }}"  @if(auth()->user()->role==3) disabled @endif><i class="fa fa-calendar" aria-hidden="true"></i>
                </div>
                <div class="item caledndericon">
                    <input type="text" name="end_date" id="datetimepicker7" placeholder="End Date" autocomplete="off" value="{{ $data->end_date }}"  @if(auth()->user()->role==3) disabled @endif ><i class="fa fa-calendar" aria-hidden="true"></i>
                </div>
                <input type="hidden" name="idd" value="{{ $data->id }}" >
                <div class="item">
                    <select name="project_type_id" required @if(auth()->user()->role==3) disabled @endif>
                        <option value=""> Project Type </option>
                        @foreach($list_project_types as $list_project_type)
                        <option value="{{ $list_project_type->id }}" @if( $list_project_type->id == $data->project_type_id )  selected @endif>{{ $list_project_type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="item">
                    <select name="status" @if(auth()->user()->role==3) disabled @endif required>
                        <option value=""> Select Status </option>
                        <option value="1" @if($data->project_status==1) {{"selected"}} @endif>Active</option>
                        <option value="0" @if($data->project_status==0) {{"selected"}} @endif>InActive</option>
                    </select>
                </div>
            </div>
            <div class="item1">
                    <textarea id="w3review" class="ckeditor" name="description" rows="4" cols="50"  @if(auth()->user()->role==3) disabled @endif>{{$data->description}}</textarea>
                </div>
            &nbsp;
            <!-- <div class="company_info">
               <div class="row">
               </div>
               <label class="font-weight-bold">Uploade Docoment:</label>
            </div>

            <table class="table table-striped table-bordered">
               <thead>
                  <tr>
                     <th scope="col">Description</th>
                     <th scope="col">Image </th>
                     <th scope="col">Date</th>
                     <th scope="col">Uploaded By</th>
                     <th scope="col">Action</th>
                  </tr>
               </thead>
               <tbody>
                @foreach( $data->projectAttachment as $value)
                        <tr>
                            <th scope="col">{{ $value->title!=''?$value->title:'' }}</th>
                            <?php
                               $imgs = json_decode($value->attachment);
                               if(!empty($imgs)){
                              ?>
                            <th scope="col">
                               @foreach($imgs as $img)
                                <?php
                                $img_pdf = $img;
                                $check = substr($img_pdf, strpos($img_pdf, ".") + 1);
                               ?>
                               @if($check=="pdf")
                            <img src="{{URL('project_attachments')}}/download.png" class="css-class" alt="alt text" width="100">
                            @else
                               <img src="{{URL('project_attachments')}}/{{$img}}" class="css-class" alt="alt text" width="100" >
                               @endif
                               @endforeach
                                <?php }else{ ?>
                                       <th> </th>
                              <?php   } ?>
                            </th>
                             <?php
                                  $date = $value->created_at;
                                  $date_formate = date("d-M-Y", strtotime($date));
                              ?>
                             <th scope="col">{{$date_formate}}</th>
                             <th scope="col">{{$data->userFillName->user->first_name}} {{$data->userFillName->user->last_name}}</th>
                            <th scope="col">
                             <a href="{{url('admin/uploade_attachment')}}/{{$value->id}}">Download</a>
                            </th>
                        </tr>
                         @endforeach
               </tbody>
            </table>
           @if(auth()->user()->role!=3)
                <div class="colums project">
                <div class="item">
                  <input type="file" name="file[]"  autocomplete="off"  multiple>
                </div>
                 <div class="item">
                    <textarea id="w3review" name="title" rows="4" cols="50" placeholder="Enter Description"></textarea>
                </div>
              </div> -->
            <div class="btn-block">
               <button type="submit" href="/">Update</button>
            </div>
            <!-- @endif -->
         </form>
      </div>
</div>
<!-- <script src="{{ asset('ckeditor/ckeditor.js')}}"></script> -->
<script type="text/javascript">
   $(document).ready(function () {   
       $('#datetimepicker6').datetimepicker();
       $('#datetimepicker7').datetimepicker({
   useCurrent: false //Important! See issue #1075
   });
       $("#datetimepicker6").on("dp.change", function (e) {
           $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
       });
       $("#datetimepicker7").on("dp.change", function (e) {
           $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
       });
   });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
    });
</script>
@endsection

