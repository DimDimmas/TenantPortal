@extends('layouts.moa_main')

    @section('title')
        <title>MOA | Employee</title>
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
                                <h4 class="card-title">Employee List</h4>
                            </div>
                            </div>
                            <div class="iq-card-body">
                            <div class="table-responsive">
                                <div class="row justify-content-between">
                                    <div class="col-sm-12 col-md-6">
                                        <div id="user_list_datatable_info" class="dataTables_filter">
                                        <form class="mr-3 position-relative">
                                            <div class="form-group mb-0">
                                                <input type="search" class="form-control" id="exampleInputSearch" placeholder="Search" aria-controls="user-list-table">
                                            </div>
                                        </form>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="user-list-files d-flex float-right">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Add New</button>
                                            <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"  aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                   <div class="modal-content">
                                                      <div class="modal-header">
                                                         <h5 class="modal-title">Modal title</h5>
                                                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                         <span aria-hidden="true">&times;</span>
                                                         </button>
                                                      </div>
                                                      <div class="modal-body">
                                                        <p>Modal body text goes here.</p>
                                                        <div class="form-row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="validationDefault01">First name</label>
                                                                <input type="text" class="form-control" id="validationDefault01" required>
                                                             </div>
                                                             <div class="col-md-6 mb-3">
                                                                <label for="validationDefault02">Last name</label>
                                                                <input type="text" class="form-control" id="validationDefault02" required>
                                                             </div>
                                                             <div class="col-md-6 mb-3">
                                                                <label for="validationDefaultUsername">Username</label>
                                                                <div class="input-group">
                                                                   <div class="input-group-prepend">
                                                                      <span class="input-group-text" id="inputGroupPrepend2">@</span>
                                                                   </div>
                                                                   <input type="text" class="form-control" id="validationDefaultUsername"  aria-describedby="inputGroupPrepend2" required>
                                                                </div>
                                                             </div>
                                                             <div class="col-md-6 mb-3">
                                                                <label for="validationDefault03">City</label>
                                                                <input type="text" class="form-control" id="validationDefault03" required>
                                                             </div>
                                                             <div class="col-md-6 mb-3">
                                                                <label for="validationDefault04">State</label>
                                                                <select class="form-control" id="validationDefault04" required>
                                                                   <option selected disabled value="">Choose...</option>
                                                                   <option>...</option>
                                                                </select>
                                                             </div>
                                                             <div class="col-md-6 mb-3">
                                                                <label for="validationDefault05">Zip</label>
                                                                <input type="text" class="form-control" id="validationDefault05" required>
                                                             </div>
                                                        </div>

                                                      </div>
                                                      <div class="modal-footer">
                                                         <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                         <button type="button" class="btn btn-primary">Save changes</button>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                        <a class="btn btn-primary" href="{{ route('employee.create') }}" >
                                            </i>Add New
                                            </a>
                                        <a class="iq-bg-primary" href="javascript:void();">
                                            Export
                                            </a>
                                            <a class="iq-bg-primary" href="javascript:void();">
                                            Import
                                            </a>
                                        </div>
                                    </div>
                                </div>


                                <table id="user-list-table" class="table table-hover table-striped table-bordered mt-4" role="grid" aria-describedby="user-list-page-info">
					<thead>
                                            <tr>
					    <th>No</th>
					    <th>Picture</th>
                                            <th>Name</th>
					    <th>NIK</th>
                                            <th>Department</th>
                                            <th>Job Title</th>
                                            <th>Superior</th>
					    <th>Join Date</th>
					    <th>Status</th>
					    <th>Mobile</th>
                                            <th>Date of Birth</th>
					    <th>Gender</th>
					    <th>Marital Status</th>
                                            <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
					    @if(count($emp_data))
                                                @foreach($emp_data as $key => $emp)
                                                <tr>
						    <td>{{ ++$key }}</td>
						    <td>{{ $emp -> picture}}</td>
                                                    <td>{{ ucfirst($emp -> name)}}</td>
                                                    <td>{{ $emp -> nik}}</td>
						    <td>{{ $emp -> dept_id}}</td>
                                            	    <td>{{ $emp -> job_title_id}}</td>
                                            	    <td>{{ $emp -> superior_id}}</td>
                                            	    <td>{{ $emp -> join_date}}</td>
                                            	    <td>{{ $emp -> emp_status_id}}</td>
                                            	    <td>{{ $emp -> mobile}}</td>
                                            	    <td>{{ $emp -> dateofbirth}}</td>
                                            	    <td>{{ $emp -> gender}}</td>
                                            	    <td>{{ $emp -> marital_status}}</td>
						    
                                                    <td>
                                                        <a class="iq-bg-primary" data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit" href="{{ route('employee.edit',$employee->id) }}">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                           @endif 
                                            
                                        </tbody>
				</table> 



                            </div>
                                <div class="row justify-content-between mt-3">
                                    <div id="user-list-page-info" class="col-md-6">
                                        <span>Showing 1 to 5 of 5 entries</span>
                                    </div>
                                    <div class="col-md-6">
                                        <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-end mb-0">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                            </li>
                                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">Next</a>
                                            </li>
                                        </ul>
                                        </nav>
                                    </div>
                                </div>
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
