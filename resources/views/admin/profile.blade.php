@extends('layouts.admin')
@section('content')
<!-- /. NAV SIDE  -->
<div id="page-wrapper">
<div class="header"></div>
<div id="page-inner">
<div class="row">
   <div class="col-md-6">
      <h2 class="page-header">Admin</h2>
   </div>
  
</div>
<div ></div>
@if(session()->has('message'))
<div class="alert alert-success">
   {{ session()->get('message') }}
</div>
@endif



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
   	
   </tbody>
   
</table>

@endsection
