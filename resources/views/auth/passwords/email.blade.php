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
    <link rel="stylesheet" href="{{url('/logincss/css/login.css')}}">
</head>
<body class="hold-transition login-page passwords new">
    <div class="login-box">
    <!--<div class="login-logo p-3 mb-0">
        <a class="text-primary font-weight-bold" href="/"><b>Construction </b>Management Software</a>
      </div> --!>
      <!-- /.login-logo -->
      <div class="card">
      <div class="card-left">
        <div class="logo">
          <h1 class="text-white"><span>VST</span> Pvt. Ltd. </h1>
        </div>
      </div>
        <div class="card-body login-card-body">
          <div class="construction_login_page">
                <h3 class="login-box-msg font-weight-bold">Forgot Password?</h3>
                <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
                @if (session('status'))
                    <div class="alert alert-success" role="alert" style='display:block'>
                        {{ session('status') }}
                    </div>
                @endif

              <form method="POST" action="{{ route('password.email') }}" class="new_user" >
				@csrf
				<div class="input-group">
				  <label for="user_email">Email</label><br />
				</div>
				<div class="input-group mb-3">
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fa fa-envelope"></span>
                        </div>
                    </div>
				    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">

                    @error('email')
                        <span class="invalid-feedback" role="alert" style='display:block'>
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
				</div>
				<div class="row">
				  <div class="col-12">
					<button type="submit" class="btn sign_up_button">
						{{ __('Send Password Reset Link') }}
					</button>
				  </div>
				</div>
			</form></div>

			<div class="row forgot_links">
				<div class="col-6 text-right">
				  <a href="{{ route('login') }}">Log in</a>
				</div>
				<div class="col-6 text-left">
				  <a href="{{ route('register') }}">Sign up</a>
				</div>
			</div>


        </div>
        <!-- /.login-card-body -->
      </div>
    </div>
  </body>
  @endsection
