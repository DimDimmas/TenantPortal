@extends('layouts.moa_main')

    @section('title')
        <title>MOA | Job Titel</title>
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
                                <h4 class="card-title">EJob Titel</h4>
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
                                        <a class="btn btn-primary" href="#" >
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
                                <table id="user-list-table" class="table table-hover table-bordered mt-4" role="grid" aria-describedby="user-list-page-info">
					                <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name </th>
                                            <th>Department </th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
					                    
                                            @foreach($jobtitel_data as $key => $jobtitel)
                                                <tr>
                                                    <td>{{ $jobtitel_data->firstItem() + $key }}</td>
                                                    <td>{{ ucfirst($jobtitel -> name)}}</td>
                                                    <td>{{ $jobtitel -> dept -> name }}</td>
                                                    
                                                    <td>
                                                        <a class="iq-bg-primary" data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit" href="#">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                        <a class="iq-bg-primary" data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete" href="#">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                             
                                    </tbody>
				                </table> 
                                


                            </div>
                            <div class="row justify-content-between mt-3">
                                <div id="user-list-page-info" class="col-md-6">
                                    <span>Showing {{$jobtitel_data->firstItem()}} to {{$jobtitel_data->lastItem()}}  of {{ $jobtitel_data->total()}} entries</span>
                                </div>
                                <div class="col-md-6">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-end mb-0">
                                            <li >
                                                {{ $jobtitel_data->links() }}
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
