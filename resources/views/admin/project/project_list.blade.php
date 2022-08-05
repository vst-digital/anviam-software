@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Sweet Alert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- /. NAV SIDE  -->
<div id="page-wrapper">
<div class="header">
</div>
<div id="page-inner">

<div class="row">
   <div class="col-md-6">
      <h2 class="page-header">Project List</h2>
   </div>

   @if($role != 3)
   <div class="col-md-6 btn_form" href="google.com">
      <a href="{{URL::to('admin/project_create')}}"><button  class="btn btn-primary float-right">Create</button></a>
   </div>
   @endif
</div>
@if(session()->has('message'))
<div class="alert alert-success">
   {{ session()->get('message') }}
</div>
@endif
  <?php
    $search = '';
    if(isset($_GET['search'])){
        $search = $_GET['search'];
    }
  ?>

    <div class="searchforms">
        <form action='' >
                <div class='col-md-2'>
                    <input type='text' name='search' class='form-control' value="{{ $search }}" placeholder='Project Name'/>
                </div>
                <div class='col-md-2'>
                    <input type='submit' class='btn btn-primary' value='Search'/>
                </div>
                <div class='col-md-2 pl-0'>
                    <a href="{{ url('admin/project_list') }}" class='btn btn-primary'>Reset</a>
                </div>
        </form>
    </div>
<div>

<table class="table table-striped table-bordered">
   <thead>
      <tr>
         <!-- <th scope="col">Name</th> -->
         <th scope="col">Project Name</th>
         <th scope="col">Description</th>
         <th scope="col">Start Date</th>
         <th scope="col">End Date</th>
         <th scope="col">Status</th>
         <th scope="col">Actions</th>
      </tr>
   </thead>
   <tbody>
        @if(count($list_projects) > 0)
            @foreach($list_projects as $list_project)
            <tr>
                <!-- <th scope="col">{{$list_project->name}}</th> -->
                <th scope="col">{{$list_project->name}}</th>
                <th scope="col">{!!$list_project->description!!}</th>
                <th scope="col">{{$list_project->start_date}}</th>
                <th scope="col">{{$list_project->end_date}}</th>
                <th scope="col">{{ $list_project->project_status==1 ?'Active':'InActive' }}</th>
                <td>
                  <a href="{{url('admin/edit_project')}}/{{@$list_project->id}}" > <i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;
                    @if($role != 3)
                    <a href="{{url('admin/detele_project')}}/{{$list_project->id}}" class="button delete-confirm">  <i class="fa fa-trash" aria-hidden="true"></i></a>
                    @else

                    @endif
                </td>
            </tr>
            @endforeach
        @else
        <tr>
            <td colspan='7'>No Project List found</td>
        </tr>
        @endif
   </tbody>
</table>
   <div class="float-right">
              {{ $list_projects->links() }}
         </div>
<script type="text/javascript">
   $('.delete-confirm').on('click', function (event) {
       event.preventDefault();
       const url = $(this).attr('href');
       swal({
           title: 'Are you sure?',
           text: 'This record and it`s details will be permanently deleted!',
           icon: 'warning',
           buttons: ["Cancel", "Yes!"],
       }).then(function(value) {
           if (value) {
               window.location.href = url;
           }
       });
   });

   $(document).ready(function(){
       $('.status_change').on('change', function() {
           var status = $(this).val();
           var id = $(this).data('id');
           $("#loader").show();

           $.ajax({
           type:'POST',
           url:'{{url("admin/status_change")}}' ,
           data:   { _token: "{{csrf_token()}}", status: status ,id:id  },
           success:function(data) {
               $("#msg").html(data.msg);
               // $("#loader").hide();
               setTimeout(function() {
                           $("#loader").hide();
               }, 1000)
           }
           });
       });
   });

</script>
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
