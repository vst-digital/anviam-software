@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Sweet Alert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
<script type="text/javascript"  src=" https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
</head>
<!-- /. NAV SIDE  -->
<div id="page-wrapper">
<div class="header"></div>
<div id="page-inner">
   <body>
      @if(session()->has('message'))
      <div class="alert alert-success">
         {{ session()->get('message') }}
      </div>
      @endif
      <div class="btn_form" href="google.com"><a href="{{ url()->previous() }}"><button  class="btn btn-primary float-right">Back</button></a>
      </div>
      <div class="testbox">
         <form method="POST" action="{{url('admin/save_company')}}">
            @csrf
            <div class="company_info">
               <div class="row">
               </div>
               <label class="font-weight-bold">User personal details</label>
            </div>
            <div class="colums">
               <div class="item">
                  <input id="name" type="text" name="fname" required placeholder="First Name" value="{{ $company_detail->user->first_name }}" readonly />
               </div>
               <div class="item">
                  <input id="name" type="text" name="lname" required placeholder="Last Name" value="{{ $company_detail->user->last_name }}" readonly/>
               </div>
               <div class="item">
                  <input id="phone" type="tel" name="phone" required placeholder="Phone Number" value="{{ $company_detail->user->phone_number }}" readonly/>
               </div>
               <div class="item">
                  <input id="email" type="text" name="email" required placeholder="Email" value="{{ $company_detail->user->email }}" readonly/>
               </div>
            </div>
            &nbsp;
            <div class="company_info">
               <div class="row">
               </div>
               <label class="font-weight-bold">User company details</label>
            </div>
            <div class="colums">
               <div class="item">
                  <input id="company_name" type="text" name="company_name" placeholder="Company Name" value="{{$company_detail->company_name}}" readonly/>
               </div>
               <div class="item">
                  <input id="company_number" type="text"   name="company_number" readonly placeholder="Phone Number" value="{{$company_detail->company_number}}"/>
               </div>
               <div class="item">
                  <input id="street" type="text" name="street" readonly placeholder="Street" value="{{$company_detail->street}}"/>
               </div>
               <div class="item">
                  <input id="city" type="text"   name="city" readonly placeholder="City" value="{{$company_detail->city}}"/>
               </div>
               <div class="item">
                  <input id="state" type="text"   name="state" readonly placeholder="State" value="{{$company_detail->state}}"/>
               </div>
               <div class="item">
                  <input id="zip" type="text" name="zip" readonly placeholder="Zip" value="{{$company_detail->zip}}"/>
               </div>
               <div class="item">
                  <input id="country" type="text" name="country" readonly placeholder="Country" value="{{$company_detail->country}}"/>
               </div>
            </div>
         </form>
      </div>
</div>
@endsection