@extends('layouts.admin')
@section('content')
<!-- /. NAV SIDE  -->
<div id="page-wrapper">
<div class="header"></div>
<div id="page-inner">

<div class="row">
   <div class="col-md-6">
      <h2 class="page-header">Companies</h2>
   </div>
</div>
@if(session()->has('message'))
<div class="alert alert-success">
   {{ session()->get('message') }}
</div>
@endif
@if(session()->has('error'))
<div class="alert alert-danger">
   {{ session()->get('error') }}
</div>
@endif


 <?php
    $company_name = '';
    if(isset($_GET['company_name'])){
     $company_name = $_GET['company_name'];
    }
   $email = '';
    if(isset($_GET['email'])){
     $email = $_GET['email'];
    }
?>

  <div class="searchforms">
      <form action='' >
          <div class='col-md-2'>
              <input type='text' name='company_name' class='form-control' autocomplete="off" value="{{ $company_name }}" placeholder=' Company Name'/>
          </div>
          <div class='col-md-2'>
              <input type='text' name='email' class='form-control' autocomplete="off" value="{{ $email }}" placeholder='Email'/>
          </div>
          <div class='col-md-2'>
              <input type='submit' class='btn btn-primary' value='Search'/>
          </div>
      </form>
  </div>


<table  id="example" class="table table-striped table-bordered" style="width:100%">
   <thead>
      <tr>
         <th scope="col">Sr. No</th>
         <th scope="col">Company Name</th>
         <th scope="col">Admin Email</th>
         <th scope="col">Registration Date</th>
         <th scope="col">Status</th>
         <th scope="col">Actions</th>
      </tr>
   </thead>
   <tbody>
        @if(count($company_details) > 0)
            <?php $i = 1;?>
            @foreach($company_details as $company_detail)
            <tr>
                <th scope="row"> {{$i}}</th>
                <td>{{ $company_detail->company_name }}</td>
                <td>{{ @$company_detail->user->email }}</td>
                <?php
                    $originalDate = $company_detail->created_at;
                    $newDate = date("M-d-Y", strtotime($originalDate));
                    ?>
                <td>{{$newDate}}</td>
                <td>
                    <select name="status" class="status_change form-control" data-id="{{@$company_detail->user->id}}" >
                    <option value="1" @if(@$company_detail->user->status == 1) selected @endif >Approve</option>
                    <option value="0" @if(@$company_detail->user->status == 0) selected @endif>Unapprove</option>
                    </select>
                </td>
                <td>
                    <a href="{{url('admin/view_company')}}/{{$company_detail->id}}">  <i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;
                    <a href="{{url('admin/edit_company')}}/{{@$company_detail->user->id}}" > <i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;
                    <a href="{{url('admin/detele_company')}}/{{$company_detail->user_id}}" class="button delete-confirm">  <i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </td>
            </tr>
            <?php $i++;?>
            @endforeach
        @else
            <tr>
                <td colspan='7'>No Company found!</td>
            </tr>
        @endif
   </tbody>
</table>
         <div class="float-right">
              {{ $company_details->links() }}
         </div>
<div class="overlay-spinner" style="display: none;"></div>
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
            $(".overlay-spinner").show();

           $.ajax({
              type:'POST',
              url:'{{url("admin/status_change")}}' ,
             data:   { _token: "{{csrf_token()}}", status: status ,id:id  },
              success:function(data) {
                 $("#msg").html(data.msg);
                  setTimeout(function() {
                            $(".overlay-spinner").hide();
                 }, 1000)
              }
           });
    });
   });

</script>
@endsection
<style type="text/css">
</style>
