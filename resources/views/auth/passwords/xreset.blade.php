<!DOCTYPE html>
<html lang="en">
<head>
	<title>{{ __('Reset Password') }}</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="/image/png" href="{{asset ('login_v2/images/icons/favicon.ico') }}"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset ('login_v2/vendor/bootstrap/css/bootstrap.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset ('login_v2/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset ('login_v2/fonts/iconic/css/material-design-iconic-font.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset ('login_v2/vendor/animate/animate.css') }}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{asset ('login_v2/vendor/css-hamburgers/hamburgers.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset ('login_v2/vendor/animsition/css/animsition.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset ('login_v2/vendor/select2/select2.min.css') }}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{asset ('login_v2/vendor/daterangepicker/daterangepicker.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset ('login_v2/css/util.css') }}">
	<link rel="stylesheet" type="text/css" href="{{asset ('login_v2/css/main.css') }}">
<!--===============================================================================================-->
</head>
<body>
	<div class="limiter">
	
	<div class="container-login100" style="background-image: url('login_v3/images/bg-01.jpg');">
		<div class="wrap-login100 p-l-40 p-r-40 p-t-80 p-b-30">
			<form method="POST" action="{{ route('password.update') }} class="login100-form validate-form">
				<span class="login100-form-title p-b-37">
					Reset Password
				</span>
				@csrf

                        	<input type="hidden" name="token" value="{{ $token }}">
				<div class="wrap-input100 validate-input m-b-20" data-validate="Enter email">
					<input id="email" class="input100 @error('email') is-invalid @enderror" type="email" value="{{ $email ?? old('email') }}" name="email" required autocomplete="email" autofocus  placeholder="email">
					<span class="focus-input100"></span>
					@error('email')
                                    		<span class="invalid-feedback" role="alert">
                                        		<strong>{{ $message }}</strong>
                                    		</span>
                                	@enderror
				</div>

				<div class="wrap-input100 validate-input m-b-25" data-validate = "Enter password">
					<input id="password" class="input100 @error('password') is-invalid @enderror" type="password" name="pass" required autocomplete="new-password" placeholder="Enter password">
					<span class="focus-input100"></span>
					@error('password')
                                    		<span class="invalid-feedback" role="alert">
                                        		<strong>{{ $message }}</strong>
                                    		</span>
                                	@enderror
				</div>

				<div class="wrap-input100 validate-input m-b-25" data-validate = "Confirm password">
                                        <input id="password-confirm" class="input100" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm password">
                                        <span class="focus-input100"></span>
  
                                </div>
				<div class="container-login100-form-btn">
					<button class="login100-form-btn">
						{{ __('Reset Password') }}
					</button>
				</div> 
			</form>
			<a class="txt4 hov1" href="{{ route('login') }}">{{ __('Login') }}</a>
			
		</div>
	</div>
	
</div>

	<!-- <div id="dropDownSelect1"></div> -->
	
<!--===============================================================================================-->
	<script src="{{asset ('login_v2/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{asset ('login_v2/vendor/animsition/js/animsition.min.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{asset ('login_v2/vendor/bootstrap/js/popper.js') }}"></script>
	<script src="{{asset ('login_v2/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{asset ('login_v2/vendor/select2/select2.min.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{asset ('login_v2/vendor/daterangepicker/moment.min.js') }}"></script>
	<script src="{{asset ('login_v2/vendor/daterangepicker/daterangepicker.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{asset ('login_v2/vendor/countdowntime/countdowntime.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{asset ('login_v2/js/main.js') }}"></script>

</body>
</html>
