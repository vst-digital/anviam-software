@extends('layouts.admin')
@section('content')


    <div id="page-wrapper">

        <div class="header">
        </div>

        <div id="page-inner">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="page-header">Transmittals List</h2>
                </div>
                <div class="col-md-6 btn_form">
                   <!--  <a href="{{URL::to('admin/storage_list_documents')}}">
                        <button  class="btn btn-primary float-right">All Documents</button>
                    </a> -->
                    <a href="{{URL::to('admin/document_create')}}">
                        <button  class="btn btn-primary float-right">Create</button>
                    </a>
                </div>
            </div>
        <div>

        @if(session()->has('message'))
            <div class="alert alert-success">
            {{ session()->get('message') }}
            </div>
        @endif
        <?php
        $project_id = $search = '';
        if(isset($_GET['project_id'])){
            $project_id = $_GET['project_id'];
        }
        if(isset($_GET['search'])){
            $search = $_GET['search'];
        }
        ?>
        <div class="searchforms">
            <form action='' >
                <div class='col-md-4 pl-0'>
                    <select name="project_id" class='form-control' >
                        <option value=''> Select Project </option>
                        @if(count($list_projects) > 0)
                            @foreach($list_projects as $val)
                                <option <?php if($project_id == $val->id){ echo 'selected'; } ?> value='{{ $val->id }}'>{{ $val->name }}</option>
                            @endforeach
                        @else
                            <option>No Project</option>
                        @endif
                    </select>
                </div>
                <div class='col-md-4 pl-0'>
                    <input type='text' name='search' value="{{ $search }}" class='form-control' placeholder='Title'/>
                </div>

                <div class='col-md-2 pl-0'>
                    <input type='submit' class='btn btn-primary' value='Search'/>
                </div>
                <div class='col-md-2 pl-0'>
                    <a href="{{ url('admin/document_storage') }}" class='btn btn-primary'>Reset</a>
                </div>
            </form>
        </div>

        <table  id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">Sr no.</th>
                    <th scope="col">Title</th>
                    <th scope="col">Project</th>
                    <th scope="col">Date</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(count($documents) > 0)
                    <?php
                    $i=1;
                    if(isset($_GET['page'])){
                        if($_GET['page'] != 1){
                            $i = ($_GET['page'] * 10) + 1;
                        }
                    }
                    ?>
                    @foreach($documents as $val)
                        <tr>
                            <th scope="col">{{@$i}}</th>
                            <th scope="col">{!!@$val->title!!}</th>
                            <th scope="col">{{@$val->projectName->name}}</th>
                            <th scope="col">{{@$val->created_at}}</th>
                            <td>
                                @if($role != 3)
                                    <a href="{{url('admin/edit_document')}}/{{@$val->id}}" > <i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;
                                    <a href="{{url('admin/detele_document')}}/{{@$val->id}}" class="button delete-confirm">  <i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                @else
                                <a href="{{url('admin/edit_document')}}/{{@$val->id}}" > <i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;
                                @endif
                            </td>
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                @else
                    <tr><td colspan='7'>No Document found</td></tr>
                @endif
            </tbody>
        </table>
        <div class="float-right">
            {{ $documents->withQueryString()->links() }}
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
