@extends('layouts.admin')
@section('content')
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <!-- Sweet Alert -->
     <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
    <script type="text/javascript"  src=" https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
 <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
<!-- /. NAV SIDE  -->

<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-6"><h2 class="page-header">Project Type</h2></div>
                <div class="col-md-6 btn_form">
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#createProjectType">Create</button>
                </div>
            </div>

            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif

            <table  id="example" class="table table-striped table-bordered" style="width:100%">
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
                                <td>{{ $project_type->description }}</td>
                                <td>{{ $project_type->status == 1?'Active':'InActive' }}</td>

                                <td>
                                <a href="{{url('admin/detele_project_type')}}/{{$project_type->id}}" class="button delete-confirm">  <i class="fa fa-trash" aria-hidden="true"></i></a>

                                <span style='cursor:pointer;' data-toggle="modal" class="editprojectType" data-id='{{$project_type->id}}' data-target="#editProjectType"><i class="fa fa-edit" aria-hidden="true"></i></span>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan='4'>No Project type found</td></tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="editProjectType" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <form action="{{url('admin/update_project_type')}}" method="POST" class='popuproleform'>
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
                                <input type="text" value="" class="form-control projecttypename" name="name" placeholder="Enter Name" required>
                                <input type="hidden" name="idd" value="" class='edit_id'>
                            </div>


                            <div class="form-group">
                                <label for="email">Status</label>
                                <select name="status" class="form-control projecttypestatus">
                                    <option value="1">Active</option>
                                    <option value="0">InActive</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="email">Description</label>
                                <textarea id="w3review" name="description" rows="4" cols="60" class="projecttypedes" ></textarea>
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
                    <textarea id="w3review" name="description" rows="4" cols="60" placeholder="Enter Description"></textarea>
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
@endsection


