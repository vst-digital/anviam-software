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
<div id="page-wrapper">
<div class="header"></div>
<div id="page-inner">
<div class="row">
   <div class="col-md-6">
      <h2 class="page-header"></h2>
   </div>
</div>
<div class="btn_form" href="google.com"><a href="{{ url()->previous() }}">
   <button  class="btn btn-primary float-right">Back</button></a>
</div>
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
<div class="testbox">
   <form method="POST" action="{{url('admin/edit_user',$user->id)}}">
      @csrf
      <div class="company_info">
         <div class="row">
         </div>
         <label class="font-weight-bold">Update Users details below:</label>
      </div>
      <div class="colums">
         <div class="item">
            <input id="name" type="text" name="fname" required placeholder="First Name" value="{{ $user->first_name }}"/>
         </div>
         <div class="item">
            <input id="name" type="text" name="lname" required placeholder="Last Name" value="{{ $user->last_name }}"/>
         </div>
         <div class="item">
            <input id="mobile_num" type="tel" name="phone" required placeholder="Phone Number"  value="{{ $user->phone_number }}"/>
            <p id="mobile_validation"></p>
         </div>
         <div class="item">
            <input type="text" name="email" required placeholder="Email" id="email"  value="{{ $user->email }}"/>
            <p id="email_validation"></p>
         </div>
         <div class="item">
            <input id="txtNewPassword" type="password" name="password" placeholder="Password (6 Characters minimum)" />
         </div>
         <div class="item">
            <input id="txtConfirmPassword" type="password" name="c_password" placeholder="Password Confirmation" />
            <p style="color:green;" id="CheckPasswordMatch"></p>
         </div>
         <div class="item">
            <select name="role_id">
                <option disabled value>Select User Role</option>
                @foreach($roles as $role)
                    <option value="{{$role->id}}">{{ $role->name }}</option>
                @endforeach
            </select>
         </div>
         <div class="item">
          <?php 
             $permissions_id = $user->permissions->pluck('module_id')->toArray();
          ?>
            <select name="department_id">
                <option disabled value>Select Department</option>
                @foreach($departments as $department)
                    <option value="{{$department->id}}" @if($user->department_id == $department->id) selected @endif>{{ $department->name }}</option>
                @endforeach
            </select>
         </div>
         
          <p class="permissions-css"><strong>Permissions</strong> </p>
         
          <p class="item newuser-css">
               @foreach($vstmodules as $key=> $vstmodule)
                <input type="checkbox" name="permissions[]" value="{{$vstmodule->id}}" @if (in_array($vstmodule->id,$permissions_id )) checked @endif>
                <label> {{ $vstmodule->name }}</label>
                @endforeach
            </p>
         <input type='hidden' value='{{ $user->id }}' name='userid' id='userid'/>
         
         <div class="btn-block">
            <button type="submit" href="/">Update User</button>
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
          var userid = $('#userid').val();
         $.ajax({
            type:'POST',
            url:'{{url("admin/check_email")}}' ,
           data:   { _token: "{{csrf_token()}}", email: email, userid: userid},
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
   });
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
