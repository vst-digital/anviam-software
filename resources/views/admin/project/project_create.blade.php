@extends('layouts.admin')
@section('content')
<div id="page-wrapper">
<div class="header"></div>
<div id="page-inner">
    <input type='hidden' value="{{url('admin/attachment_add')}}" id = 'formuploadurl'/>
    <input type='hidden' value="{{url('admin/attachment_rm')}}" id = 'rmurl'/>
    <input type='hidden' value="{{csrf_token()}}" id = 'csrfToken'/>
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
         <form method="POST" action="{{url('admin/save_project')}}" enctype="multipart/form-data">
            @csrf
            <div class="company_info">
               <div class="row">
               </div>
               <label class="font-weight-bold">Enter project details below:</label>
            </div>
            <div class="colums project">
                <div class="item">
                    <input type="text" name="name" required placeholder="Name" /></div>
                <div class="item">
                    <!-- <input type="text" name="name" required placeholder="Name" /> -->
                    <select name="users[]" required class='selectuser' multiple="multiple" >
                        @foreach($users_data as $val)
                            <option value='{{ $val->id }}'>{{ $val->first_name }} {{ $val->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="item caledndericon">
                     <input type="text" name="start_date" id="datetimepicker6"   placeholder="Start Date" autocomplete="off" required><i class="fa fa-calendar" aria-hidden="true"></i>

                    <!-- <input type="date" name="start_date" placeholder="Start Date" autocomplete="off" required> -->
                    <!-- <input type="text" name="start_date" id = "start_date" placeholder="Start Date" autocomplete="off" required> -->
                </div>
                <div class="item caledndericon">
                    <input type="text" name="end_date" id="datetimepicker7"   placeholder="End Date" autocomplete="off" required><i class="fa fa-calendar" aria-hidden="true"></i>

                    <!-- <input type="date" name="end_date"  placeholder="End Date" autocomplete="off" required> -->
                    <!-- <input type="text" name="end_date" id = "end_date" placeholder="End Date" autocomplete="off" required> -->
                </div>
                <div class="item">
                    <select name="project_type_id" required>
                        <option value=""> Project Type </option>
                        @foreach($list_project_types as $list_project_type)
                        <option value="{{ $list_project_type->id }}">{{ $list_project_type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="item">
                    <select name="status" required>
                        <option value=""> Select Status </option>
                        <option value="1">Active</option>
                        <option value="0">InActive</option>
                    </select>
                </div>
                
            </div>
            <div class="item1">
                    <textarea name="description" class="ckeditor" rows="4" cols="50" placeholder="Enter Description"></textarea>
                </div>
            <!-- &nbsp;
            <div class="company_info">
               <div class="row">
               </div>
               <label class="font-weight-bold">Uploade Docoment:</label>
            </div>
            <div class="colums project">
                <textarea name="title" rows="4" cols="50" placeholder="Enter Description"></textarea>
                <div class="dropzone" id="myDropzone" style="width:100%;">
            </div> -->

            <div class="btn-block">
               <button type="submit" href="/">Create</button>
            </div>
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

<!--   <script>
  $( function() {
    $( ".datepicker" ).datepicker();
  } );
  </script> -->

<script type="text/javascript">
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
    });
</script>
<style type="text/css">
    input[type="date"] {
  position: relative;
  padding: 10px;
}

input[type="date"]::-webkit-calendar-picker-indicator {
  color: transparent;
  background: none;
  z-index: 1;
}

input[type="date"]:before {
  color: transparent;
  background: none;
  display: block;
  font-family: 'FontAwesome';
  content: '\f073';
  /* This is the calendar icon in FontAwesome */
  width: 15px;
  height: 20px;
  position: absolute;
  top: 12px;
  right: 6px;
  color: #999;
}
</style>




@endsection
