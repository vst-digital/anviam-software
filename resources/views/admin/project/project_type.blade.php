@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Sweet Alert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
<!-- /. NAV SIDE  -->
<div id="page-wrapper">
<div id="page-inner">
   <div class="row">
      <div class="col-md-6">
         <h2 class="page-header">Project Type</h2>
      </div>
      <div class="col-md-6 btn_form">
         <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#createProjectType">Create</button>
      </div>
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
                <input type='text' name='search' class='form-control' value="{{ $search }}" placeholder=' Name'/>
            </div>
            <div class='col-md-2'>
                <input type='submit' class='btn btn-primary' value='Search'/>
            </div>
            <div class='col-md-2 pl-0'>
                    <a href="{{ url('admin/project_type') }}" class='btn btn-primary'>Reset</a>
                </div>
        </form>
    </div>


   <table class="table table-striped table-bordered" style="width:100%">
      <thead>
         <tr>
            <th scope="col">Name</th>
            <th scope="col">Description</th>
            <th scope="col">Status</th>
            <th scope="col">Actions</th>
         </tr>
      </thead>
      <tbody>
         @if(count($project_types) > 0)
         @foreach($project_types as $project_type)
         <tr>
            <td>{{ $project_type->name }}</td>
            <td>{!!$project_type->description!!}</td>
            <td>{{ $project_type->status == 1?'Active':'InActive' }}</td>
            <td>
             
               <span style='cursor:pointer;' data-toggle="modal" class="editprojectType" data-id='{{$project_type->id}}' data-target="#editProjectType"><i class="fa fa-edit" aria-hidden="true"></i></span>
                 <a href="{{url('admin/detele_project_type')}}/{{$project_type->id}}" class="button delete-confirm">  <i class="fa fa-trash" aria-hidden="true"></i></a>
            </td>
         </tr>
         @endforeach
         @else
         <tr>
            <td colspan='4'>No Project type found</td>
         </tr>
         @endif
      </tbody>
   </table>
          <div class="float-right">
              {{ $project_types->links() }}
         </div>

</div>
<div class="modal fade" id="editProjectType" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
   <form action="{{url('admin/update_project_type')}}" method="POST" class='popuproleform'>
      @csrf
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h3 class="modal-title" id="exampleModalLongTitle">Edit your project details
               </h3>
            </div>
            <div class="modal-body">
               <div class="form-group">
                  <label for="email">Name</label>
                  <input type="text" value="" class="form-control projecttypename" name="name" placeholder="Enter Name" required>
                  <input type="hidden" name="idd" value="" class='edit_id'>
                  
               </div>
               <div class="form-group">
                  <label for="email">Status</label>
                  <select name="status" class="form-control getprojecttypestatus">
                     <option value="1">Active</option>
                     <option value="0">InActive</option>
                  </select>
               </div>
               <div class="form-group">
                  <label for="email">Description</label>
                  <textarea id="w3review" class="ckeditor" name="description" rows="4" cols="60" class="projecttypedes"  maxlength="10"></textarea>
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
<input type='hidden' value="{{ route('project.get-project-type') }}" id='getprojecttype' />
<input type='hidden' value="{{ csrf_token() }}" id='csrfToken' />
<div class="modal fade" id="createProjectType" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
   <form action="{{url('admin/save_project_type')}}" method="POST"  class='popuproleform'>
      @csrf
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h3 class="modal-title" id="exampleModalLongTitle">Create Project Type
               </h3>
            </div>
            <div class="modal-body">
               <div class="form-group">
                  <label for="email">Name</label>
                  <input type="text" class="form-control" name="name" placeholder="Enter Name" required>
               </div>
               <div class="form-group">
                  <label for="email">Status</label>
                  <select class="form-control" name="status" required>
                     <option value=""> Select Status </option>
                     <option value="1"> Active </option>
                     <option value="0"> InActive </option>
                  </select>
               </div>
               <div class="form-group">
                  <label for="email">Description</label>
                  <textarea id="w3review" class="ckeditor" name="description" rows="4" cols="60" placeholder="Enter Description"></textarea>
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

<!-- <script src="{{ asset('ckeditor/ckeditor.js')}}"></script>
 -->
<script type="text/javascript">
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
    });
</script>
@endsection
