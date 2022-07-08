<!DOCTYPE html>
<html lang="en">
<head>
	<title>{{ __('Login') }}</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="{{asset ('login_v3/images/icons/favicon.ico') }}"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset ('login_v3/vendor/bootstrap/css/bootstrap.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset ('login_v3/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset ('login_v3/fonts/iconic/css/material-design-iconic-font.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset ('login_v3/vendor/animate/animate.css') }}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{asset ('login_v3/vendor/css-hamburgers/hamburgers.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset ('login_v3/vendor/animsition/css/animsition.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset ('login_v3/vendor/select2/select2.min.css') }}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{asset ('login_v3/vendor/daterangepicker/daterangepicker.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset ('login_v3/css/util.css') }}">
	<link rel="stylesheet" type="text/css" href="{{asset ('login_v3/css/main.css') }}">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100" style="background-image: url('moa/login_v3/images/bg-01.jpg');">
			<div class="wrap-login100">
				<form method="POST" action="{{ route('login') }}" class="login100-form validate-form">
				@csrf
					
					<span class="login100-form-title p-b-34 p-t-27">
						Log in
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Enter Email Address">
                        			<input id="email" class="input100" type="email" name="email" required value="{{ old('email') }}" placeholder="Email Address">
                        				<span class="focus-input100" data-placeholder="&#xf207;"></span>
    							</span>
                            			@error('email')
                                			<span class="invalid-feedback" role="alert">
                                    				<strong>{{ $message }}</strong>
                                			</span>
                            			@enderror
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input id="password" class="input100 @error('password') is-invalid @enderror" type="password" name="password" required placeholder="Password">
                        				<span class="focus-input100" data-placeholder="&#xf191;"></span>
                            			@error('password')
                                			<span class="invalid-feedback" role="alert">
                                    				<strong>{{ $message }}</strong>
                                				</span>
                            			@enderror
					</div>
					<div class="container-login100-form-btn">
						<button type="submit" class="login100-form-btn">
                            				{{ __('Login') }}
						</button>
					</div>

					<div class="text-right p-t-50">
						<a class="txt1" href="{{ route('password.request') }}">
                            				{{ __('Forgot Your Password?') }}
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="{{asset ('login_v3/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{asset ('login_v3/vendor/animsition/js/animsition.min.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{asset ('login_v3/vendor/bootstrap/js/popper.js') }}"></script>
	<script src="{{asset ('login_v3/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{asset ('login_v3/vendor/select2/select2.min.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{asset ('login_v3/vendor/daterangepicker/moment.min.js') }}"></script>
	<script src="{{asset ('login_v3/vendor/daterangepicker/daterangepicker.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{asset ('login_v3/vendor/countdowntime/countdowntime.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{asset ('login_v3/js/main.js') }}"></script>

</body>
</html>
