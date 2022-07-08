@extends('layouts.moa_main')

    @section('title')
        <title>MOA | Master Account</title>
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
                                <h4 class="card-title">Account Masters</h4>
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
                                                                <label for="validationDefault03">Name</label>
                                                                <input type="text" class="form-control" id="validationDefault03" required>
                                                             </div>
                                                             <div class="col-md-6 mb-3">
                                                                <label for="validationDefault03">masteraccount Code</label>
                                                                <input type="text" class="form-control" id="validationDefault03" required>
                                                             </div>
                                                             <div class="col-md-6 mb-3">
                                                                <label for="validationDefault03">Address1</label>
                                                                <input type="text" class="form-control" id="validationDefault03" required>
                                                             </div>
                                                             <div class="col-md-6 mb-3">
                                                                <label for="validationDefault03">Address2</label>
                                                                <input type="text" class="form-control" id="validationDefault03" required>
                                                             </div>
                                                             <div class="col-md-6 mb-3">
                                                                <label for="validationDefault04">City</label>
                                                                <select class="form-control" id="validationDefault04" required>
                                                                   <option selected disabled value="">Choose...</option>
                                                                   <option>...</option>
                                                                </select>
                                                             </div>
                                                             <div class="col-md-6 mb-3">
                                                                <label for="validationDefault05">Postcode</label>
                                                                <input type="text" class="form-control" id="validationDefault05" required>
                                                             </div>
                                                             <div class="col-md-6 mb-3">
                                                                <label for="validationDefault04">Currency</label>
                                                                <select class="form-control" id="validationDefault04" required>
                                                                   <option selected disabled value="">Choose...</option>
                                                                   <option>...</option>
                                                                </select>
                                                             </div>
                                                             <div class="col-md-6 mb-3">
                                                                <label for="validationDefault05">Phone</label>
                                                                <input type="text" class="form-control" id="validationDefault05" required>
                                                             </div>
                                                             <div class="col-md-6 mb-3">
                                                                <label for="validationDefault04">Tax Code</label>
                                                                <select class="form-control" id="validationDefault04" required>
                                                                   <option selected disabled value="">Choose...</option>
                                                                   <option>...</option>
                                                                </select>
                                                             </div>
                                                             <div class="col-md-6 mb-3">
                                                                <label for="validationDefault05">NPWD</label>
                                                                <input type="text" class="form-control" id="validationDefault05" required>
                                                             </div>
                                                             <div class="col-md-6 mb-3">
                                                                <label for="validationDefault05">Permit No.</label>
                                                                <input type="text" class="form-control" id="validationDefault05" required>
                                                             </div>
                                                             <div class="col-md-6 mb-3">
                                                                <label for="exampleInputdate">Permit Date</label>
                                                                <input type="date" class="form-control" id="exampleInputdate" value="2019-12-18">
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
                                            <th>A/C no</th>
                                            <th>A/C Name</th>
                                            <th>Currency</th>
					                        <th>A/C Type</th>
                                            <th>A/C Spec</th>
                                            <th>A/C No. Parent</th>
                                            <th>A/C Group</th>
                                            <th>level</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
					                    
                                            @foreach($masteraccount_data as $key => $masteraccount)
                                                <tr>
                                                    <td>{{ $masteraccount_data->firstItem() + $key }}</td>
                                                    <td>{{ $masteraccount -> acc_no}}</td>
                                                    <td>{{ $masteraccount -> name}}</td>
                                                    <td>{{ $masteraccount -> currency -> currency_cd}}</td>
                                                    <td>{{ $masteraccount -> acc_type-> eacc_type_name}}</td>
                                            	    <td>{{ $masteraccount -> acc_spec-> acc_spec_name}}</td>
                                            	    <td>{{ $masteraccount -> acc_parent_no}}</td>
                                            	    <td>{{ $masteraccount -> acc_group-> acc_group_name }}</td>
                                            	    <td>{{ $masteraccount -> level_id}}</td>
                                            	    <td>{{ $masteraccount -> status}}</td>
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
                                    <span>Showing {{$masteraccount_data->firstItem()}} to {{$masteraccount_data->lastItem()}}  of {{ $masteraccount_data->total()}} entries</span>
                                </div>
                                <div class="col-md-6">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-end mb-0">
                                            <li >
                                                {{ $masteraccount_data->links() }}
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
