@extends('layouts.admin')
@section('content')
<!-- /. NAV SIDE  -->
<div id="page-wrapper">
<div class="header"></div>
<div id="page-inner">
<div class="row">
   <div class="col-md-6">
      <h2 class="page-header">Users</h2>
   </div>
   <div class="col-md-6 btn_form" href="google.com"><a href="{{URL::to('admin/user_create')}}"><button  class="btn btn-primary float-right">Create</button></a></div>
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

    $email = '';
    if(isset($_GET['email'])){
     $email = $_GET['email'];
    }
  ?>

    <div class="searchforms">
        <form action='' >
            <div class='col-md-2'>
                <input type='text' name='name' class='form-control' autocomplete="off" value="{{ $name }}" placeholder=' Name'/>
            </div>
            <div class='col-md-2'>
                <input type='text' name='email' class='form-control' value="{{ $email }}" placeholder=' Email' autocomplete="off" />
            </div>

            <div class='col-md-2'>
                <input type='submit' class='btn btn-primary' value='Search'/>
            </div>
            <div class='col-md-2 pl-0'>
                <a href="{{ url('admin/user') }}" class='btn btn-primary'>Reset</a>
            </div>
        </form>
    </div>

<table  id="example" class="table table-striped table-bordered" style="width:100%">
   <thead>
      <tr>
         <th scope="col">Sr. No</th>
         <th scope="col">Name</th>
         <th scope="col">Email</th>
         <th scope="col">Role</th>
         <th scope="col">Registration Date</th>
         <th scope="col">Actions</th>
      </tr>
   </thead>
   <tbody>
        @if(count($users_data) > 0)
            <?php $i = 1; ?>
            @foreach($users_data as $user)
            <tr>
                <th scope="row"> {{$i}}</th>
                <td>{{ $user->first_name }} {{$user->last_name}}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if(@$user->company_role->name != '')
                    {{ @$user->company_role->name }}
                    @else
                    Admin
                    @endif
                </td>
                <?php
                    $originalDate = $user->created_at;
                    $newDate = date("M-d-Y", strtotime($originalDate));
                    ?>
                <td>{{$newDate}}</td>
                <td>
                    <a href="{{url('admin/view_role_user')}}/{{$user->id}}">  <i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;
                    @if($userid != $user->id)
                    <a href="{{url('admin/edit_user')}}/{{$user->id}}" > <i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;
                    @else
                    <a href="{{url('admin/edit_company')}}/{{$user->id}}" > <i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;
                    @endif
                    @if($userid != $user->id)
                    <a href="{{url('admin/detele_role_user')}}/{{$user->id}}" class="button delete-confirm">  <i class="fa fa-trash-o" aria-hidden="true"></i></a>
                    @endif
                </td>
            </tr>
            <?php $i++;?>
            @endforeach
        @else
            <tr>
                <td colspan='7'>No User found!</td>
            </tr>
        @endif
   </tbody>
</table>
    <div class="float-right">
              {{ $users_data->links() }}
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
