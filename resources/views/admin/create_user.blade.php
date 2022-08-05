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
   <div class="header">
   </div>
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
   <div class="btn_form" href="google.com"><a href="{{ url()->previous() }}"><button  class="btn btn-primary float-right">Back</button></a>
   </div>
   <div class="testbox">
      <form method="POST" action="{{url('admin/save_user')}}">
         @csrf
         <div class="company_info">
            <div class="row">
            </div>
            <label class="font-weight-bold">Enter Users details below:</label>
         </div>
         <div class="colums">
            <div class="item">
               <input id="name" type="text" name="first_name" required placeholder="First Name"  value="{{ old('first_name') }}"/>
            </div>
            <div class="item">
               <input id="name" type="text" name="last_name" required placeholder="Last Name" value="{{ old('last_name') }}"/>
            </div>
            <div class="item">
               <input id="mobile_num" type="tel" name="phone_number" required placeholder="Phone Number" value="{{ old('phone_number') }}"/>
               <p id="mobile_validation"></p>
            </div>
            <div class="item">
               <input class="@error('email') is-invalid @enderror" type="email" name="email" required placeholder="Email" id="email" value="{{ old('email') }}"/>
               <p id="email_validation"></p>
            </div>
            <div class="item">
               <input  class="@error('password') is-invalid @enderror" id="txtNewPassword" type="password" name="password" required placeholder="Password (6 Characters minimum)" minlength="6" />
            </div>
            <div class="item">
               <input class="@error('c_password') is-invalid @enderror" id="txtConfirmPassword" type="password" name="c_password" required placeholder="Password Confirmation" minlength="6" />
               <p style="color:green;" id="CheckPasswordMatch"></p>
            </div>
            <div class="item">
               <select name="role_id" required>
                    <option disabled selected value>Select User Role</option>
                    @foreach($roles as $role)
                        <option value="{{$role->id}}">{{ $role->name }}</option>
                    @endforeach
               </select>
            </div>
            <div class="item">
               <select name="department_id" required>
                    <option disabled selected value>Select Departments</option>
                    @foreach($departments as $department)
                        <option value="{{$department->id}}">{{ $department->name }}</option>
                    @endforeach
               </select>
            </div>
             <p class="permissions-css"><strong>Permissions</strong> </p>
             <p class="item newuser-css">
               @foreach($vstmodules as $vstmodule)
                <input type="checkbox" name="permissions[]" value="{{$vstmodule->id}}">
                <label> {{ $vstmodule->name }}</label>
                @endforeach
            </p>
            
            <div class="btn-block">
               <button type="submit" href="/">Add User</button>
            </div>
      </form>
      </div>
   </div>
   @endsection
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script type="text/javascript">
      $(document).ready(function(){
        $("#email").blur(function(){
             var email = $(this).val();
            $.ajax({
               type:'POST',
               url:'{{url("admin/check_email")}}' ,
              data:   { _token: "{{csrf_token()}}", email: email},
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
      function checkPasswordMatch() {
        var password = $("#txtNewPassword").val();
        var confirmPassword = $("#txtConfirmPassword").val();
        if (password != confirmPassword)
            $("#CheckPasswordMatch").html("Passwords does not match!");
        else
            $("#CheckPasswordMatch").html("Passwords match.");
      }
      $(document).ready(function () {
       $("#txtConfirmPassword").keyup(checkPasswordMatch);
       $("#txtNewPassword").keyup(checkNewPassMatch);
      });
      function checkNewPassMatch() {
        var password = $("#txtNewPassword").val();
        var confirmPassword = $("#txtConfirmPassword").val();
        if (password != '' || confirmPassword != ''){
            if (password != confirmPassword)
                $("#CheckPasswordMatch").html("Passwords does not match!");
            else
                $("#CheckPasswordMatch").html("Passwords match.");
        }else{
            $("#CheckPasswordMatch").html("");
        }

      }
      $(document).ready(function(){
          $("#mobile_num").on("blur", function(){
                var mobNum = $(this).val();
                var filter = /^\d*(?:\.\d{1,2})?$/;
                  if (filter.test(mobNum)) {
                    if(mobNum.length==10){
                        //  alert("valid");
                     $("#mobile_validation").hide();
                     } else {
                       $("#mobile_validation").show();
                         $("#mobile_validation").text("Please put 10  digit mobile number");
                         $("#mobile_validation").css("color" , "red")
                         //$(this).val("")
                         return false;
                      }
                    }
                    else {
                      $("#mobile_validation").show();
                      $("#mobile_validation").text("Not a valid number");
                      $("#mobile_validation").css("color" , "red")
                     // $(this).val("")
                      return false;
                   }
          });
        });

          $(document).ready(function(){
          $("#company_num").on("blur", function(){
                var mobNum = $(this).val();
                var filter = /^\d*(?:\.\d{1,2})?$/;
                  if (filter.test(mobNum)) {
                    if(mobNum.length==10){
                        //  alert("valid");
                     $("#company_num_validation").hide();
                     } else {
                       $("#company_num_validation").show();
                         $("#company_num_validation").text("Please put 10  digit mobile number");
                         $("#company_num_validation").css("color" , "red")
                         $(this).val("")
                         return false;
                      }
                    }
                    else {
                      $("#company_num_validation").show();
                      $("#company_num_validation").text("Not a valid number");
                      $("#company_num_validation").css("color" , "red")
                      $(this).val("")
                      return false;
                   }
          });
        });
   </script>

   <style>
     .newuser-css input[type=checkbox] {
          display: block;
          width: 10%;
          float: left;    
      }
      .newuser-css label{
        float: left;
      }
   </style>
