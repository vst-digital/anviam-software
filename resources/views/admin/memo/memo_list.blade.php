@extends('layouts.admin')
@section('content')

<link rel="stylesheet" href="{{url('/dragable/akottr.css')}}" />
<link rel="stylesheet" href="{{url('/dragable/dragtable.css')}}" />
<link rel="stylesheet" href="{{url('/dragable/jquerysctipttop.css')}}" />
<link rel="stylesheet" href="{{url('/dragable/reset.css')}}" />

<script src="{{url('/dragable/jquery-1.9.0.min.js')}}"></script>
<script src="{{url('/dragable/jquery-ui.min.js')}}"></script> 
<script src="{{url('/dragable/jquery.dragtable.js')}}"></script>

    <div id="page-wrapper">
        <div class="header">
        </div>

        <div id="page-inner">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="page-header">Issues List</h2>
                </div>
                @if($role != 3)
                    <div class="col-md-6 btn_form" href="google.com">
                        <a href="{{URL::to('admin/issue_create')}}"><button  class="btn btn-primary float-right">Create</button></a>
                    </div>
                @endif
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
                    <select name="project_id" required class='form-control' >
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
                    <a href="{{ url('admin/issue_list') }}" class='btn btn-primary'>Reset</a>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-md-12">

        <table class="table localStorageTable sar-table table-striped table-bordered" id="table1">
            <thead>
                <tr>
                    <th>Sr no.</th>
                    <th id="lst_user1">Name</th>
                    <th id="lst_nic1e">Ticket No.</th>
                    <th id="lst_sys1tem">Date</th>
                    <th id="lst_iow1ait">Location</th>
                    <th id="lst_idl1e">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(count($list_memos) > 0)
                    <?php
                    $i=1;
                    if(isset($_GET['page'])){
                        if($_GET['page'] != 1){
                            $i = ($_GET['page'] * 10) + 1;
                        }
                    }
                    ?>
                    @foreach($list_memos as $list_memo)
                        <tr>
                            <th scope="col">{{@$i}}</th>
                            <th scope="col">{{@$list_memo->projectName->name}}</th>
                            <th scope="col">{{@$list_memo->project_number}}</th>
                            <th scope="col">{{@$list_memo->datetime}}</th>
                            <th scope="col">{{@$list_memo->location}}</th>
                            <td>
                                @if($role != 3)
                                    
                                    <a href="{{url('admin/edit_issue')}}/{{@$list_memo->id}}" > <i class="fa fa-edit" aria-hidden="true" data-toggle="tooltip" title="Edit"></i></a>&nbsp;

                                    <a href="{{url('admin/check_issue')}}/{{@$list_memo->id}}" class="button">  <i class="fa fa-reply" aria-hidden="true" data-toggle="tooltip" title="Reply"></i></a> &nbsp;

                                    <a href="{{url('admin/detele_issue')}}/{{@$list_memo->id}}" class="button delete-confirm" data-toggle="tooltip" title="Delete">  <i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                    

                                @else
                                    <a href="{{url('admin/check_issue')}}/{{@$list_memo->id}}" >
                                    <i class="fa fa-eye" data-toggle="tooltip" title="View Issue"></i>
                                    <!-- <i class="fa fa-edit" aria-hidden="true"></i> --></a>
                                @endif
                            </td>
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                @else
                    <tr><td colspan='7'>No Issue found</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

        <!-- <table  id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">Sr no.</th>
                    <th scope="col">Name</th>
                    <th scope="col">Issue Number</th>
                    <th scope="col">Date</th>
                    <th scope="col">Location</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            
        </table> -->
        <div class="float-right">
            {{ $list_memos->withQueryString()->links() }}
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

$(document).ready(function() {
 $('.localStorageTable').dragtable({
        persistState: function(table) {
          if (!window.localStorage) return;
          var ss = window.localStorage;
          table.el.find('th').each(function(i) {
            if(this.id != '') {table.sortOrder[this.id]=i;}
          });
          ss.setItem('tableorder',JSON.stringify(table.sortOrder));
        },
        restoreState: eval('(' + window.localStorage.getItem('tableorder') + ')')
    });
 });

$(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
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
    #table1 thead th {
    background: #a9a4a436 !important;
    border: 1px solid #c3c3c3;
    }
    i.fa.fa-eye {
    padding: 0 15px;
    }
</style>
@endsection
