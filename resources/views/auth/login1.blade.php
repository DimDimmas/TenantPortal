<!DOCTYPE html>
<html lang="en">
<head>
	<title>{{ __('Login') }}</title>
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
		<div class="wrap-login100 p-l-55 p-r-55 p-t-80 p-b-30">
			<form class="login100-form validate-form">
				<span class="login100-form-title p-b-37">
					Sign In
				</span>

				<div class="wrap-input100 validate-input m-b-20" data-validate="Enter username or email">
					<input class="input100" type="text" name="username" placeholder="username or email">
					<span class="focus-input100"></span>
				</div>

				<div class="wrap-input100 validate-input m-b-25" data-validate = "Enter password">
					<input class="input100" type="password" name="pass" placeholder="password">
					<span class="focus-input100"></span>
				</div>

				<div class="container-login100-form-btn">
					<button class="login100-form-btn">
						Sign In
					</button>
				</div>

			<!--	<div class="text-center p-t-57 p-b-20">
					<span class="txt1">
						Or login with
					</span>
				</div>

				<div class="flex-c p-b-112">
					<a href="#" class="login100-social-item">
						<i class="fa fa-facebook-f"></i>
					</a>

					<a href="#" class="login100-social-item">
						<img src="{{asset ('login1/images/icons/icon-google.png')}}" alt="GOOGLE">
					</a>
				</div>
-->
				<div style="margin-top:25px" class="text-right">
					<a href="#"  class="txt4 hov1">
						Forgot password?
					</a>
				</div> 
			</form>

			
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
