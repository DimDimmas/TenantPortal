<!doctype html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>{{ __('Login') }}</title>
      <!-- Favicon -->
      <link rel="shortcut icon" href="moa/images/favicon.ico" />
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="moa/css/bootstrap.min.css">
      <!-- Typography CSS -->
      <link rel="stylesheet" href="moa/css/typography.css">
      <!-- Style CSS -->
      <link rel="stylesheet" href="moa/css/style.css">
      <!-- Responsive CSS -->
      <link rel="stylesheet" href="moa/css/responsive.css">
   </head>
   <body>
      <!-- loader Start -->
      <div id="loading">
         <div id="loading-center">
         </div>
      </div>
      <!-- loader END -->
        <!-- Sign in Start -->
        <section class="sign-in-page">
            <div class="container bg-white p-0">
                <div class="row no-gutters">
                    <div class="col-sm-6 align-self-center">
                        <div class="sign-in-from">
                            <h1 class="mb-0">Sign in</h1>
			                    {{-- <form method="GET" action="{{ route('login') }}" class="mt-4"> --}}
			                    <form method="POST" action="{{ url('post-login') }}" class="mt-4">
                                 {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="token">Token</label>
                                    <input type="text" name="password" id="password" class="form-control mb-0 @error('password') is-invalid @enderror" id="token" placeholder="Enter Token" value="{{ old('password') }}" required autocomplete="password" autofocus>
					                @error('password')
                                      	<span class="invalid-feedback" role="alert">
                                       		<strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="d-inline-block w-100">
                                    <button type="submit" class="btn btn-primary float-right">Sign in</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-6 text-center">
                        <div class="sign-in-detail text-white">
                            <a class="sign-in-logo mb-5" href="#"><img src="moa/images/logo-white.png" class="img-fluid" alt="logo"></a>
                            <div class="owl-carousel" data-autoplay="true" data-loop="true" data-nav="false" data-dots="true" data-items="1" data-items-laptop="1" data-items-tab="1" data-items-mobile="1" data-items-mobile-sm="1" data-margin="0">
                                <div class="item">
                                    <img src="moa/images/login/1.png" class="img-fluid mb-4" alt="logo">
                                    <h4 class="mb-1 text-white">Manage your orders</h4>
                                    <p>It is a long established fact that a reader will be distracted by the readable content.</p>
                                </div>
                                <div class="item">
                                    <img src="moa/images/login/1.png" class="img-fluid mb-4" alt="logo">
                                    <h4 class="mb-1 text-white">Manage your orders</h4>
                                    <p>It is a long established fact that a reader will be distracted by the readable content.</p>
                                </div>
                                <div class="item">
                                    <img src="moa/images/login/1.png" class="img-fluid mb-4" alt="logo">
                                    <h4 class="mb-1 text-white">Manage your orders</h4>
                                    <p>It is a long established fact that a reader will be distracted by the readable content.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Sign in END -->
      <!-- Optional JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      <script src="moa/js/jquery.min.js"></script>
      <script src="moa/js/popper.min.js"></script>
      <script src="moa/js/bootstrap.min.js"></script>
      <!-- Appear JavaScript -->
      <script src="moa/js/jquery.appear.js"></script>
      <!-- Countdown JavaScript -->
      <script src="moa/js/countdown.min.js"></script>
      <!-- Counterup JavaScript -->
      <script src="moa/js/waypoints.min.js"></script>
      <script src="moa/js/jquery.counterup.min.js"></script>
      <!-- Wow JavaScript -->
      <script src="moa/js/wow.min.js"></script>
      <!-- Apexcharts JavaScript -->
      <script src="moa/js/apexcharts.js"></script>
      <!-- Slick JavaScript -->
      <script src="moa/js/slick.min.js"></script>
      <!-- Select2 JavaScript -->
      <script src="moa/js/select2.min.js"></script>
      <!-- Owl Carousel JavaScript -->
      <script src="moa/js/owl.carousel.min.js"></script>
      <!-- Magnific Popup JavaScript -->
      <script src="moa/js/jquery.magnific-popup.min.js"></script>
      <!-- Smooth Scrollbar JavaScript -->
      <script src="moa/js/smooth-scrollbar.js"></script>
      <!-- Chart Custom JavaScript -->
      <script src="moa/js/chart-custom.js"></script>
      <!-- Custom JavaScript -->
      <script src="moa/js/custom.js"></script>
   </body>
</html>
