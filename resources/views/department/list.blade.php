@extends('layouts.admin')
@section('content')
<!-- /. NAV SIDE  -->
<div id="page-wrapper">
<div class="header"></div>
<div id="page-inner">
<div class="row">
   <div class="col-md-6">
      <h2 class="page-header">Department List</h2>
   </div>
   <div class="col-md-6 btn_form" href="google.com"><a href="{{URL::to('admin/department_create')}}"><button  class="btn btn-primary float-right">Create</button></a></div>
</div>
<div ></div>
@if(session()->has('message'))
<div class="alert alert-success">
   {{ session()->get('message') }}
</div>
@endif

  <?php
    $name = '';
    if(isset($_GET['name'])){
     $name = $_GET['name'];
    }

  ?>

    <div class="searchforms">
        <form action='' >
            <div class='col-md-2'>
                <input type='text' name='name' class='form-control' autocomplete="off" value="{{ $name }}" placeholder=' Name'/>
            </div>
            <div class='col-md-2'>
                <input type='submit' class='btn btn-primary' value='Search'/>
            </div>
            <div class='col-md-2 pl-0'>
                <a href="{{ url('admin/department') }}" class='btn btn-primary'>Reset</a>
            </div>
        </form>
    </div>

<table  id="example" class="table table-striped table-bordered" style="width:100%">
   <thead>
      <tr>
         <th scope="col">Sr. No</th>
         <th scope="col">Name</th>
         <th scope="col">Description</th>
         <th scope="col">Actions</th>
      </tr>
   </thead>
   <tbody>
    <?php $i=0;?>
      @foreach($department_lists as $department_list)
        <tr>
          <td>{{++$i}}</td>
          <td>{{$department_list->name}}</td>
          <td>{!! $department_list->description !!}</td>
          <td>
            <a href="{{url('admin/edit_department')}}/{{$department_list->id}}" > <i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;
            <a href="{{url('admin/detele_department')}}/{{$department_list->id}}" class="button delete-confirm">  <i class="fa fa-trash-o" aria-hidden="true"></i></a>

          </td>
        </tr>
        @endforeach
   </tbody>
</table
><div class="float-right">
    {{ $department_lists->links() }}
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


   $(document).ready(function() {
       $("#example").DataTable();
   });

</script>
@endsection
