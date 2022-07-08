@extends('layouts.moa_main')

    @section('title')
        <title>MOA | Add Employee</title>
    @endsection
    
    @section('stylesheet')
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset ('moa/images/favicon.ico')}}" />
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="{{ asset ('moa/css/bootstrap.min.css')}}">
      <!-- Typography CSS -->
      <link rel="stylesheet" href="{{ asset ('moa/css/typography.css')}}">
      <!-- Style CSS -->
      <link rel="stylesheet" href="{{ asset ('moa/css/style.css')}}">
      <!-- Responsive CSS -->
      <link rel="stylesheet" href="{{ asset ('moa/css/responsive.css')}}">
    @endsection

    @section('content')
    <div class="wrapper">
        <div id="content-page" class="content-page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="iq-card">
                            <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Add New Employee </h4>
                            </div>
                            </div>
                            <div class="iq-card-body">
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form method="post" action="{{ route('employee.store') }}">
                                    @csrf
				    <div class="form-group row align-items-center">
                                          <div class="col-md-12">
                                             <div class="profile-img-edit">
                                                <img class="profile-pic" src="moa/images/user/11.png" alt="profile-pic">
                                                <div class="p-image">
                                                  <i class="ri-pencil-line upload-button"></i>
                                                  <input class="file-upload" type="file" accept="image/*"/>
                                               </div>
                                             </div>
                                          </div>
                                       </div>
				    <div class="form-group col-sm-6">
                                             <label for="name">Name:</label>
                                             <input type="text" class="form-control" id="name" value="Jhon">
                                    </div>
				    <div class="form-group col-sm-6">
                                        <label for="nik">NIK :</label>
                                        <input type="text" class="form-control" name="nik"/>
                                    </div>      
                                    <div class="form-group col-sm-6">
                                        <label for="dept">Department :</label>
                                        <input type="text" class="form-control" name="dept"/>
                                    </div>
				    <div class="form-group col-sm-6">
                                             <label>Designation :</label>
                                             <select class="form-control" id="exampleFormControlSelect1">
                                                <option selected="">Staff</option>
                                                <option>Senior Staff</option>
                                                <option>Junior Supervisor</option>
                                                <option>Supervisor</option>
                                                <option>Senior Supervisor</option>
                                                <option>Junior Manager</option>
						<option>Manager</option>
						<option>Senior Manager</option>
                                             </select>
                                          </div>
    
				    <div class="form-group col-sm-6">
                                        <label for="position">Job Title :</label>
                                        <input type="text" class="form-control" name="position"/>
                                    </div>
				    <div class="form-group col-sm-6">
                                        <label for="superior">Superior :</label>
                                        <input type="text" class="form-control" name="superior"/>
                                    </div>
				    <div class="form-group col-sm-6">
					<label for="join_date">Join Date:</label>
					<input type="date" class="form-control" id="join_date" value="2019-12-18">
                                    </div>
				    <div class="form-group col-sm-6">
                                    	<label>Status:</label>
                                             <select class="form-control" id="exampleFormControlSelect1">
                                                <option selected="">Employee</option>
                                                <option>Probation</option>
                                                <option>Contract</option>
                                                <option>Daily Worker</option>
                                                <option>Suspend</option>
						<option>Retired</option>
                                             </select>
                                    </div>
				    <div class="form-group col-sm-6">
                                          <label for="cno">Contact Number:</label>
                                          <input type="text" class="form-control" id="cno" value="001 2536 123 458">
                                    </div>
				    <div class="form-group col-sm-6">
                                    	<label class="d-block">Gender:</label>
                                             <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="customRadio6" name="customRadio1" class="custom-control-input" checked="">
                                                <label class="custom-control-label" for="customRadio6"> Male </label>
                                             </div>
                                             <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="customRadio7" name="customRadio1" class="custom-control-input">
                                                <label class="custom-control-label" for="customRadio7"> Female </label>
                                             </div>
                                    </div>				    
				    <div class="form-group col-sm-6">
                                             <label>Marital Status:</label>
                                             <select class="form-control" id="exampleFormControlSelect1">
                                                <option selected="">Single</option>
                                                <option>Married</option>
                                                <option>Widowed</option>
                                                <option>Divorced</option>
                                                <option>Separated </option>
                                             </select>
                                          </div>
				    <div class="form-group col-sm-6">
                                        <label for="dob">Date Of Birth:</label>
                                     	<input type="date" class="form-control" id="exampleInputdate" value="2019-12-18">
                                    </div>

                                    <div class="form-group col-sm-6 user-list-files d-flex ">
                                        <button type="submit" class="btn btn-primary-outline iq-bg-primary">Save</button>
                                        <a class="iq-bg-primary" href="{{ route('employee.index') }}" >Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection
    
    @section('javascript')
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{ asset ('moa/js/jquery.min.js')}}"></script>
      <script src="{{ asset ('moa/js/popper.min.js')}}"></script>
      <script src="{{ asset ('moa/js/bootstrap.min.js')}}"></script>
      <!-- Appear JavaScript -->
      <script src="{{ asset ('moa/js/jquery.appear.js')}}"></script>
      <!-- Countdown JavaScript -->
      <script src="{{ asset ('moa/js/countdown.min.js')}}"></script>
      <!-- Counterup JavaScript -->
      <script src="{{ asset ('moa/js/waypoints.min.js')}}"></script>
      <script src="{{ asset ('moa/js/jquery.counterup.min.js')}}"></script>
      <!-- Wow JavaScript -->
      <script src="{{ asset ('moa/js/wow.min.js')}}"></script>
      <!-- Apexcharts JavaScript -->
      <script src="{{ asset ('moa/js/apexcharts.js')}}"></script>
      <!-- Slick JavaScript -->
      <script src="{{ asset ('moa/js/slick.min.js')}}"></script>
      <!-- Select2 JavaScript -->
      <script src="{{ asset ('moa/js/select2.min.js')}}"></script>
      <!-- Owl Carousel JavaScript -->
      <script src="{{ asset ('moa/js/owl.carousel.min.js')}}"></script>
      <!-- Magnific Popup JavaScript -->
      <script src="{{ asset ('moa/js/jquery.magnific-popup.min.js')}}"></script>
      <!-- Smooth Scrollbar JavaScript -->
      <script src="{{ asset ('moa/js/smooth-scrollbar.js')}}"></script>
      <!-- lottie JavaScript -->
      <script src="{{ asset ('moa/js/lottie.js')}}"></script>
      <!-- Chart Custom JavaScript -->
      <script src="{{ asset ('moa/js/chart-custom.js')}}"></script>
      <!-- Custom JavaScript -->
      <script src="{{ asset ('moa/js/custom.js')}}"></script>
      @endsection
