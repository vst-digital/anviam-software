@extends('layouts.admin')
@section('content')'
<!-- /. NAV SIDE  -->
<div id="page-wrapper">
<div id="page-inner">
<div class="row">
   <div class="col-md-6">
      <h2 class="page-header">Roles</h2>
   </div>
   <div class="col-md-6 btn_form">
      <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#exampleModalLong">
      Create
      </button>
   </div>
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
                <a href="{{ url('admin/role') }}" class='btn btn-primary'>Reset</a>
            </div>
        </form>
  </div>

<table  id="example" class="table table-striped table-bordered" style="width:100%">
   <thead>
      <tr>
         <th scope="col">Name</th>
         <th scope="col">Description</th>
         <th scope="col">Last Modified</th>
         <th scope="col">Actions</th>
      </tr>
   </thead>
   <tbody>
        @if(count($roles) > 0)
            <?php $i = 1; ?>
            @foreach($roles as $role)
            <tr>
                <td>{{ $role->name }}</td>
                <td>{!!$role->description!!}</td>
                <?php
                    $originalDate = $role->updated_at;
                    $newDate = date("M-d-Y", strtotime($originalDate));
                    ?>
                <td>{{$newDate}}</td>
                <td>
                    <a data-toggle="modal" href='javascript:void(0)' class="editRoles" data-target="#modelroleEdit" data-id='{{$role->id}}'><i class="fa fa-edit" aria-hidden="true" ></i></a>
                    <a href="{{url('admin/detele_role')}}/{{$role->id}}" class="button delete-confirm">  <i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </td>
            </tr>
            <?php $i++;?>
            @endforeach
        @else
            <tr>
                <td colspan='7'>No Roles found!</td>
            </tr>
        @endif
   </tbody>
</table>
<div class="float-right">
    {{ $roles->links() }}
</div>
<input type='hidden' value="{{ route('roles.get-role') }}" id='getroleroute' />
<input type='hidden' value="{{ csrf_token() }}" id='csrfToken' />
<div class="modal fade" id="modelroleEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <form action="{{url('admin/update_roless')}}" method="POST" class='popuproleform editroleform'>
        @csrf
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLongTitle">Edit
                </h3>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="email">Name</label>
                    <input type="text" value="" id='roleName' class="form-control" name="name" placeholder="Enter Role Name" required>
                    <input type="hidden" name="idd" id='roleId' value="">
                </div>
                <div class="form-group">
                    <label for="email">Description</label>
                    <textarea id="w3review" class="ckeditor" name="description" rows="4" cols="50"  @if(auth()->user()->role==3) disabled @endif>{{@$role->description}}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <input type="submit" value="Update" class="btn btn-primary" style='width:auto;'>
            </div>
        </div>
        </div>
    </form>
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

</script>
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
   <form action="{{url('admin/save_role')}}" method="POST"  class='popuproleform addroleform'>
      @csrf
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h3 class="modal-title" id="exampleModalLongTitle">Create New Company Role
               </h3>
            </div>
            <div class="modal-body">
               <div class="form-group">
                  <label for="email">Name</label>
                  <input type="text" class="form-control" name="name" placeholder="Enter Role Name" required id='roleName1'>
               </div>
               <div class="form-group">
                  <label for="email">Description</label>
                  <textarea id="w3review" class="ckeditor" name="description" rows="4" cols="60" placeholder="Enter Role Description"></textarea>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               <input type="submit" name="submit" value="Create" class="btn btn-primary" style="width:auto;">
            </div>
         </div>
      </div>
   </form>
</div>



<script type="text/javascript">
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
    });
</script>


@endsection
