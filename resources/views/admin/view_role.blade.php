@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Sweet Alert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
<script type="text/javascript"  src=" https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script> 
<link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
<!-- /. NAV SIDE  -->
<div id="page-wrapper">
<div class="header"> </div>
<div id="page-inner">
</head>
<body>
   @if(session()->has('message'))
   <div class="alert alert-success">
      {{ session()->get('message') }}
   </div>
   @endif
   <div class="btn_form" href="google.com"><a href="{{ url()->previous() }}"><button  class="btn btn-primary float-right">Back</button></a>
   </div>
   <div class="testbox">
      <form method="POST" action="{{url('admin/save_user')}}">
         @csrf
         <div class="company_info">
            <div class="row">
            </div>
            <label class="font-weight-bold">User personal details:</label>
         </div>
         <div class="colums">
            <div class="item">           
               <input id="name" type="text" name="fname" required value="{{$role_detail->first_name}}" placeholder="First Name" readonly />
            </div>
            <div class="item">           
               <input id="name" type="text" name="lname" value="{{$role_detail->last_name}}" required placeholder="Last Name" readonly/>
            </div>
            <div class="item">
               <input id="mobile_num" type="tel" value="{{$role_detail->phone_number}}" name="phone" required placeholder="Phone Number" readonly/>
               <p id="mobile_validation"></p>
            </div>
            <div class="item">
               <input type="text" name="email" value="{{$role_detail->email}}" required placeholder="Email" id="email" readonly />
               <p id="email_validation"></p>
            </div>
      </form>
      </div>
   </div>
   @endsection