@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Sweet Alert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
<script type="text/javascript"  src=" https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
<!-- /. NAV SIDE  -->
</head>
<body>
   <div id="page-wrapper">
   <div class="header"></div>
   <div id="page-inner">
      <!-- <div class="row">
         <div class="col-md-6">
            <h2 class="page-header"></h2>
         </div>
      </div> -->
      @if(session()->has('message'))
      <div class="alert alert-success">
         {{ session()->get('message') }}
      </div>
      @endif
      @if ($errors->any())
      <div class="alert alert-danger">
         <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
         </ul>
      </div>
      @endif
      <div class="btn_form" href="google.com">
         <a href="{{ url()->previous() }}"><button  class="btn btn-primary float-right">Back</button></a>
      </div>
      <div class="testbox">
         <form method="POST" action="{{url('admin/update_company')}}" id="myform">
            @csrf
            <div class="company_info">
               <div class="row">
               </div>
               <label class="font-weight-bold">Enter your personal details below:</label>
            </div>
            <div class="colums">
               <div class="item">
                  <input id="name" type="text" name="first_name" required placeholder="First Name" value="{{ $company_detail->user->first_name }}" />
               </div>
               <div class="item">
                  <input id="name" type="text" name="last_name" required placeholder="Last Name" value="{{ $company_detail->user->last_name }}" />
               </div>
               <div class="item">
                  <input id="mobile_num" type="tel" name="phone_number" required placeholder="Phone Number" value="{{ $company_detail->user->phone_number }}"/>
                  <p id="mobile_validation"></p>
               </div>
               <div class="item">
                  <input id="email" type="email" name="email" required placeholder="Email" value="{{ $company_detail->user->email }}"  />
                  <p id="email_validation"></p>
               </div>
               <input type="hidden" name="idd" id="user_id" value="{{ $company_detail->user->id }}">
            </div>
            &nbsp;
            <div class="company_info">
               <div class="row">
               </div>
               <label class="font-weight-bold">Enter your Company details below:</label>
            </div>
            <div class="colums">
               <div class="item">
                  <input id="company_name" type="text" name="company_name" placeholder="Company Name" value="{{$company_detail->company_name}}" required/>
               </div>
               <div class="item">
                  <input id="company_num" type="text"   name="company_number" required placeholder="Phone Number" value="{{$company_detail->company_number}}"/>
                  <p id="company_num_validation"></p>
               </div>
               <div class="item">
                  <input id="street" type="text" name="street" required placeholder="Street" value="{{$company_detail->street}}"/>
               </div>
               <div class="item">
                  <input id="city" type="text"   name="city" required placeholder="City" value="{{$company_detail->city}}"/>
               </div>
               <div class="item">
                  <input id="state" type="text"   name="state" required placeholder="State" value="{{$company_detail->state}}"/>
               </div>
               <div class="item">
                  <input id="zip" type="text" name="zip" required placeholder="Zip" value="{{$company_detail->zip}}"/>
               </div>
               <div class="item">
                  <input id="country" type="text" name="country" required placeholder="Country" value="{{$company_detail->country}}"/>
               </div>
               <input type="hidden" name="company_id" value="{{$company_detail->user_id}}">
            </div>
            <div class="btn-block">
               <button type="submit"  >Update</button>
            </div>
         </form>
      </div>
   </div>
   @endsection
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script type="text/javascript">

      $(document).ready(function(){
            $("#myform").submit(function(e) {
                var mobNum = $("#mobile_num").val();
                var company_num = $('#company_num').val();
                var mobile = phone_validate(mobNum,'mobile_validation');
                var companymob = phone_validate(company_num,'company_num_validation');
                if(companymob && mobile){
                    return true;
                }
                return false;
            });
            $("#mobile_num").on("blur", function(e){
                var mobNum = $(this).val();
                var mobile = phone_validate(mobNum,'mobile_validation');
                if(mobile){
                    return true;
                }
                return false;
            });
            $("#company_num").on("blur", function(e){
                var mobNum = $(this).val();
                var companymob = phone_validate(mobNum,'company_num_validation');
                if(companymob){
                    return true;
                }
                return false;
            });
            $("#email").blur(function(e){
                var email = $(this).val();
                var idd = $("#user_id").val();
                $.ajax({
                    type:'POST',
                    url:'{{url("admin/edit_email")}}' ,
                    data:   { _token: "{{csrf_token()}}", email: email,idd:idd},
                    success:function(data) {
                        if(data==1){
                            $("email").val("")
                            $("#email_validation").show();
                            $("#email_validation").text("Email allready exists");
                            $("#email_validation").css("color" , "red")
                            return false;
                    }else{
                            $("#email_validation").hide();
                            return true;
                    }
                    }
                });
            });
        });
        function phone_validate(phno,clss) {
            console.log(phno+'----'+clss);
            var filter = /^[0-9-+]+$/;
            if (filter.test(phno)) {
            if(phno.length==10){
                //  alert("valid");
                $("#"+clss).hide();
                } else {
                    $("#"+clss).show();
                    $("#"+clss).text("Please put 10  digit mobile number");
                    $("#"+clss).css("color" , "red");
                    return false;
                }
            } else {
                $("#"+clss).show();
                $("#"+clss).text("Not a valid number");
                $("#"+clss).css("color" , "red");
                return false;
            }
            return true;
        }

   </script>
