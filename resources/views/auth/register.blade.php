@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">
    <title>{{config('app.name')}}</title>
    <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="{{url('/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{url('/css/font-awesome.min.css')}}">

	<link rel="stylesheet" href="{{url('/css/registration.css')}}">
</head>

<body class='login-page registrations new'>
	<div class="login-box">
		<div class="card">
			<div class="card-left">
				<div class="logo">
					<h1 class="text-white"><span>VST</span> Pvt. Ltd. </h1>
				</div>
			</div>
			@error('name')
				<span class="invalid-feedback" role="alert" style='display:block;'>
					<strong>{{ $message }}</strong>
				</span>
			@enderror
			<div class="card-body login-card-body">
				<div class="registration_signUp_page">
					<h3 class="login-box-msg font-weight-bold pb-0">Sign up</h3>
					<p class="font-weight-bold">Enter your personal details below:</p>
					<form method="POST" class='new_user' action="{{ route('register') }}"  autocomplete="off">
					  @csrf
					  <div class="row">
						<div class="col-6">
						  <div class="input-group mb-3">
							<div class="input-group-append">
							  <div class="input-group-text">
								<span class="fa fa-user"></span>
							  </div>
							</div>
							<input class="input--style-3 form-control @error('first_name') is-invalid @enderror" type="text" placeholder="First Name" name="first_name" value="{{ old('first_name') }}" required autocomplete="First Name" autofocus>
						  </div>
						</div>
						<div class="col-6">
						  <div class="input-group mb-3">
							<div class="input-group-append">
							  <div class="input-group-text">
								<span class="fa fa-user"></span>
							  </div>
							</div>
							<input class="input--style-3 form-control @error('last_name') is-invalid @enderror" type="text" placeholder="Last Name" name="last_name" value="{{ old('last_name') }}" required autocomplete="Last Name" autofocus>
						  </div>
						</div>
					  </div>
					  <div class="row">
						<div class="col-6">
						    <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fa fa-phone"></span>
                                    </div>
                                </div>
                                <input class="form-control @error('phone_number') is-invalid @enderror" type="text" placeholder="Phone Number" name="phone_number" value="{{ old('phone_number') }}" required autocomplete="Phone Number" autofocus type='number' id="mobile-num">
						    </div>
						    <p id="mobile_validation"></p>
						</div>
						<div class="col-6">
						  <div class="input-group mb-3">
							<div class="input-group-append">
							  <div class="input-group-text">
								<span class="fa fa-envelope"></span>
							  </div>
							</div>
							<input class="form-control @error('email') is-invalid @enderror" type="email" placeholder="Email" name="email"  value="{{ old('email') }}" required autocomplete="off" autofocus>
						  </div>
						@error('email')
							<span class="invalid-feedback" role="alert" style='display:block;'>
								<p style="color: red;">{{ $message }}</p>
							</span>
						@enderror
						</div>
					  </div>
					  <div class="row">
						<div class="col-6">
						  <div class="input-group mb-3">
							<div class="input-group-append">
							  <div class="input-group-text">
								<span class="fa fa-lock"></span>
							  </div>
							</div>
							<input class="form-control @error('password') is-invalid @enderror" type="password" placeholder="Password (6 characters minimum)" name="password" required autocomplete="new-password" minlength="6" autofocus id="txtNewPassword">
						  </div>
						</div>
						<div class="col-6">
						  <div class="input-group mb-3">
							<div class="input-group-append">
							  <div class="input-group-text">
								<span class="fa fa-lock"></span>
							  </div>
							</div>
							<input class="form-control @error('password_confirmation') is-invalid @enderror" type="password" placeholder="Password confirmation" name="password_confirmation"  required autocomplete="Password confirmation" autofocus id="txtConfirmPassword" minlength="6">
						  </div>
						  <p style="color:green;" id="CheckPasswordMatch"></p>
						</div>
					  </div>

					    @error('password')
							<span class="invalid-feedback" role="alert" style='display:block;'>
								<strong style="color: red;">{{ $message }}</strong>
							</span>
						@enderror
					  <hr/>
					  <div class="company_info">
					  <p class="font-weight-bold">Enter your Company details below:</p>
					  <div class="row">
						<div class="col-6">
						  <div class="input-group mb-3">
							<div class="input-group-append">
							  <div class="input-group-text">
								<span class="fa fa-location-arrow"></span>
							  </div>
							</div>
							<input class="form-control @error('company_name') is-invalid @enderror" type="text" placeholder="Company Name" name="company_name" value="{{ old('company_name') }}" required autocomplete="Company Name" autofocus>
						  </div>
						</div>
						<div class="col-6">
						  <div class="input-group mb-3">
							<div class="input-group-append">
							  <div class="input-group-text">
								<span class="fa fa-phone"></span>
							  </div>
							</div>
							<input class="form-control @error('company_number') is-invalid @enderror" type="text" placeholder="Phone Number" name="company_number" value="{{ old('company_number') }}" required autocomplete="Phone Number" id="c_number" autofocus>
						  </div>
						   <p id="company_validation"></p>
						</div>
					  </div>
					  <div class="row">
						<div class="col-6">
						  <div class="input-group mb-3">
							<div class="input-group-append">
							  <div class="input-group-text">
								<span class="fa fa-street-view"></span>
							  </div>
							</div>
							<input class="form-control @error('street') is-invalid @enderror" type="text" placeholder="Street" name="street" value="{{ old('street') }}" required autocomplete="Street" autofocus>
						  </div>
						</div>
						<div class="col-6">
						  <div class="input-group mb-3">
							<div class="input-group-append">
							  <div class="input-group-text">
								<span class="fa fa-building"></span>
							  </div>
							</div>
							<input class="form-control @error('city') is-invalid @enderror" type="text" placeholder="City" name="city" value="{{ old('city') }}" required autocomplete="City" autofocus>
						  </div>
						</div>
					  </div>
					  <div class="row">
						<div class="col-6">
						  <div class="input-group mb-3">
							<div class="input-group-append">
							  <div class="input-group-text">
								<span class="fa fa-home"></span>
							  </div>
							</div>
							<input class="form-control @error('state') is-invalid @enderror" type="text" placeholder="State" name="state" value="{{ old('state') }}" required autocomplete="State" autofocus>
						  </div>
						</div>
						<div class="col-6">
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fa fa-file-archive-o"></span>
                                    </div>
                                </div>
                                <input class="form-control @error('state') is-invalid @enderror" type="text" placeholder="Zip" name="zip" value="{{ old('zip') }}" required autocomplete="Zip" autofocus>
                                </div>
                            </div>
						</div>
					  </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                        <span class="fa fa-flag"></span>
                                        </div>
                                    </div>
                                    <input class="form-control @error('country') is-invalid @enderror" type="text" placeholder="Country" name="country" value="{{ old('country') }}" required autocomplete="Country" autofocus>
                                </div>
                            </div>
                         
                        </div>
					    <div class="row mt-3">
					    	<div class="col-3"></div>
							<div class="col-6">
								<input name="register" id="register" class="btn sign_up_button" type="submit" value="Sign up">
							</div>
							
							<div class="col-12 AlreadyLogin">
								Already have an account?<a class="" href="{{ route('login') }}">Log in</a>
							</div>
						</div>						
					</form>
				</div>
			</div>
		</div>
	</div>
    <!-- Jquery JS-->
	<script src="{{url('/js/jquery-3.4.1.min.js')}}"></script>
    <!-- Main JS-->
    <script src="{{url('/registercss/js/global.js')}}"></script>


    <script type="text/javascript">
        $(document).ready(function(){
          $("#mobile-num").on("blur", function(){
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
                        // $(this).val("")
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

          $("#c_number").on("blur", function(){
                var companyNum = $(this).val();
                var filter = /^\d*(?:\.\d{1,2})?$/;
                  if (filter.test(companyNum)) {
                    if(companyNum.length==10){
                     $("#company_validation").hide();
                     } else {
                     	 $("#company_validation").show();
                         $("#company_validation").text("Please put 10  digit mobile number");
                         $("#company_validation").css("color" , "red")
                         $(this).val("")
                         return false;
                      }
                    }
                    else {
                      $("#company_validation").show();
                      $("#company_validation").text("Not a valid number");
                      $("#company_validation").css("color" , "red")
                      $(this).val("")
                      return false;
                   }
          });
        });
				$(document).ready(function(){
						$("input").attr("autocomplete", "off");
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

    </script>


   

</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>
<!-- end document-->

@endsection
