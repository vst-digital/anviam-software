@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{config('app.name')}}</title>
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{url('/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{url('/css/font-awesome.min.css')}}">
  <link rel="stylesheet" href="{{url('/logincss/css/login.css')}}">


</head>
<body class="login-page registrations new">

	<div class="login-box">
		<div class="card">
			<div class="card-left">
				<div class="logo">
					<h1 class="text-white"><span>VST</span> Pvt. Ltd. </h1>
				</div>
			</div>
			<div class="card-body login-card-body">
				<div class="construction_login_page">
                    @if (session('message'))
                        <div class="alert alert-success" role="alert" style='display:block'>
                            {{ session('message') }}
                        </div>
                    @endif

					<h3 class="login-box-msg font-weight-bold">Sign in</h3>
					<form action="{{ route('login') }}" method="POST" class='new_user'>
						@csrf
						<div class="input-group mb-0">
							<label for="user_email">Email</label>
						</div>
						<div class="input-group mb-3">
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fa fa-envelope"></span>
								</div>
							</div>
							<input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Email address" required >
						</div>


						<div class="input-group mb-0">
							<label for="user_password">Password</label>
						</div>
						<div class="input-group">
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fa fa-lock"></span>
								</div>
							</div>
							<input type="password" name="password" id="password" class="form-control" placeholder="***********" required>
						</div>
						<!-- <div class="row">
							<div class="col-12">
								<div class="icheck-primary">
									<input type="checkbox" value="1" name="user[remember_me]" id="user_remember_me">
									<label for="user_remember_me">Remember me</label>
								</div>
							</div>
						</div> -->
						@error('email')
							<span class="invalid-feedback" role="alert" style='display:block;'>
								<strong>{{ $message }}</strong>
							</span>
						@enderror
						@error('active')
							<span class="invalid-feedback" role="alert" style='display:block;'>
								<strong>{{ $message }}</strong>
							</span>
						@enderror
						<div class="row mt-3">
							<div class="col-12">
								<input name="login" id="login" class="btn sign_up_button" type="submit" value="Log in">
							</div>
						</div>
						<a href="{{ route('password.request') }}" class="forgot">Forgot password?</a>

						<a class="sign_up_button" href="{{ route('register') }}">Sign up</a>
					</form>
				</div>
			</div>
		<!-- /.login-card-body -->
		</div>
	</div>
	<script src="{{url('/js/jquery-3.4.1.min.js')}}"></script>
</body>
</html>

@endsection
