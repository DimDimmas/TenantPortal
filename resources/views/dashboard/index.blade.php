{{-- @extends('layouts.moa_main') --}}
@extends('layouts.crm_main')


    @section('title')
        <title> Dashboard 1</title>
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
                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                            <div class="iq-card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6>Invoice Sent</h6>
                                    <span class="iq-icon"><i class="ri-information-fill"></i></span>
                                </div>
                                <div class="iq-customer-box d-flex align-items-center justify-content-between mt-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle iq-card-icon iq-bg-primary mr-2"> <i class="ri-inbox-fill"></i></div>
                                        <h2>352</h2>
                                    </div>
                                <div class="iq-map text-primary font-size-32"><i class="ri-bar-chart-grouped-line"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                            <div class="iq-card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6>Credited From Accounts</h6>
                                    <span class="iq-icon"><i class="ri-information-fill"></i></span>
                                </div>
                                <div class="iq-customer-box d-flex align-items-center justify-content-between mt-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle iq-card-icon iq-bg-danger mr-2"><i class="ri-radar-line"></i></div>
                                            <h2>$37k</h2>
                                    </div>
                                    <div class="iq-map text-danger font-size-32"><i class="ri-bar-chart-grouped-line"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                            <div class="iq-card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6>AVG Employee Costs</h6>
                                    <span class="iq-icon"><i class="ri-information-fill"></i></span>
                                </div>
                                <div class="iq-customer-box d-flex align-items-center justify-content-between mt-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle iq-card-icon iq-bg-warning mr-2"><i class="ri-price-tag-3-line"></i></div>
                                    <h2>32%</h2></div>
                                    <div class="iq-map text-warning font-size-32"><i class="ri-bar-chart-grouped-line"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                            <div class="iq-card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6>Average payment delay</h6>
                                    <span class="iq-icon"><i class="ri-information-fill"></i></span>
                                </div>
                                <div class="iq-customer-box d-flex align-items-center justify-content-between mt-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle iq-card-icon iq-bg-info mr-2"><i class="ri-refund-line"></i></div>
                                        <h2>27h</h2>
                                    </div>
                                    <div class="iq-map text-info font-size-32"><i class="ri-bar-chart-grouped-line"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-7">
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height overflow-hidden">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Invoice Stats</h4>
                            </div>
                            <div class="iq-card-header-toolbar d-flex align-items-center">
                                <div class="dropdown">
                                    <span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown">
                                    <i class="ri-more-fill"></i>
                                    </span>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="">
                                    <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                                    <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                    <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                    <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                    <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="home-chart-02"></div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-5">
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height" style="background: transparent;">
                        <div class="iq-card-body rounded p-0" style="background: url(moa/images/page-img/01.png) no-repeat;    background-size: cover; height: 423px;">
                            <div class="iq-caption">
                                <h1>450</h1>
                                <p>Invoice</p>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Open Invoices</h4>
                            </div>
                            <div class="iq-card-header-toolbar d-flex align-items-center">
                                <div class="dropdown">
                                    <span class="dropdown-toggle text-primary" id="dropdownMenuButton5" data-toggle="dropdown">
                                    <i class="ri-more-fill"></i>
                                    </span>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton5">
                                    <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                                    <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                    <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                    <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                    <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="table-responsive">
                                <table class="table mb-0 table-borderless">
                                    <thead>
                                    <tr>
                                        <th scope="col">Client</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Invoice</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">atatus</th>
                                        <th scope="col">Action</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Ira Membrit</td>
                                        <td>18/10/2019</td>
                                        <td>20156</td>
                                        <td>$1500</td>
                                        <td><div class="badge badge-pill badge-success">Paid</div></td>
                                        <td>Copy</td>
                                    </tr>
                                    <tr>
                                        <td>Pete Sariya</td>
                                        <td>26/10/2019</td>
                                        <td>7859</td>
                                        <td>$2000</td>
                                        <td><div class="badge badge-pill badge-success">Paid</div></td>
                                        <td>Send Email</td>
                                    </tr>
                                    <tr>
                                        <td>Cliff Hanger</td>
                                        <td>18/11/2019</td>
                                        <td>6396</td>
                                        <td>$2500</td>
                                        <td><div class="badge badge-pill badge-danger">Past Due</div></td>
                                        <td>Before Due</td>
                                    </tr>
                                    <tr>
                                        <td>Terry Aki</td>
                                        <td>14/12/2019</td>
                                        <td>7854</td>
                                        <td>$5000</td>
                                        <td><div class="badge badge-pill badge-success">Paid</div></td>
                                        <td>Copy</td>
                                    </tr>
                                    <tr>
                                        <td>Anna Mull</td>
                                        <td>24/12/2019</td>
                                        <td>568569</td>
                                        <td>$10000</td>
                                        <td><div class="badge badge-pill badge-success">Paid</div></td>
                                        <td>Send Email</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Monthly Invoices</h4>
                            </div>
                            <div class="iq-card-header-toolbar d-flex align-items-center">
                                <div class="dropdown">
                                    <span class="dropdown-toggle" id="dropdownMenuButton1" data-toggle="dropdown" >
                                    <i class="ri-more-fill"></i>
                                    </span>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton1" style="">
                                    <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                                    <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                    <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                    <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                    <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <ul class="suggestions-lists m-0 p-0">
                            <li class="d-flex mb-4 align-items-center">
                                <div class="profile-icon iq-bg-success"><span><i class="ri-check-fill"></i></span></div>
                                <div class="media-support-info ml-3">
                                    <h6>Camelun ios</h6>
                                    <p class="mb-0"><span class="text-success">17 paid</span> month out of 23</p>
                                </div>
                                <div class="media-support-amount ml-3">
                                    <h6><span class="text-secondary">$</span><b> 12,434.00</b></h6>
                                    <p class="mb-0">per month</p>
                                </div>
                            </li>
                            <li class="d-flex mb-4 align-items-center">
                                <div class="profile-icon iq-bg-warning"><span><i class="ri-check-fill"></i></span></div>
                                <div class="media-support-info ml-3">
                                    <h6>React</h6>
                                    <p class="mb-0"><span class="text-warning">Late payment 12 week</span> - pay invoice</p>
                                </div>
                                <div class="media-support-amount ml-3">
                                    <h6><span class="text-secondary">$</span><b> 12,434.00</b></h6>
                                    <p class="mb-0">per month</p>
                                </div>
                            </li>
                            <li class="d-flex mb-4 align-items-center">
                                <div class="profile-icon iq-bg-success"><span><i class="ri-check-fill"></i></span></div>
                                <div class="media-support-info ml-3">
                                    <h6>Camelun ios</h6>
                                    <p class="mb-0"><span class="text-success">17 paid</span> month out of 23</p>
                                </div>
                                <div class="media-support-amount ml-3">
                                    <h6><span class="text-secondary">$</span><b> 12,434.00</b></h6>
                                    <p class="mb-0">per month</p>
                                </div>
                            </li>
                            <li class="d-flex mb-4 align-items-center">
                                <div class="profile-icon iq-bg-danger"><span><i class="ri-check-fill"></i></span></div>
                                <div class="media-support-info ml-3">
                                    <h6>Camelun ios</h6>
                                    <p class="mb-0"><span class="text-danger">17 paid</span> month out of 23</p>
                                </div>
                                <div class="media-support-amount ml-3">
                                    <h6><span class="text-secondary">$</span><b> 12,434.00</b></h6>
                                    <p class="mb-0">per month</p>
                                </div>
                            </li>
                        </ul>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 row m-0 p-0">
                        <div class="col-md-6">
                            <div class="iq-card iq-card-block iq-card-stretch iq-card-height" style="background: transparent; box-shadow: none;">
                                <div class="iq-card-body p-0">
                                    <img src="{{ asset ('moa/images/booking/03.png')}}" class="img-fluid rounded w-100" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">Exchange Rates</h4>
                                    </div>
                                    <div class="iq-card-header-toolbar d-flex align-items-center">
                                        <div class="dropdown">
                                            <span class="dropdown-toggle" id="dropdownMenuButton-one" data-toggle="dropdown" >
                                                <i class="ri-more-fill"></i>
                                            </span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-one" style="">
                                                <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                                                <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                                <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                                <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                                <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="iq-card-body p-0">
                                    <div id="home-chart-01"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">Last costs</h4>
                                    </div>
                                    <div class="iq-card-header-toolbar d-flex align-items-center">
                                        <div class="dropdown">
                                            <span class="dropdown-toggle" id="dropdownMenuButton-twoa" data-toggle="dropdown" >
                                                <i class="ri-more-fill"></i>
                                            </span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-twoa" style="">
                                                <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                                                <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                                <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                                <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                                <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="iq-card-body p-0">
                                    <div id="home-chart-04 style=height: 350px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">Efficiency</h4>
                                    </div>
                                    <div class="iq-card-header-toolbar d-flex align-items-center">
                                        <div class="dropdown">
                                            <span class="dropdown-toggle" id="dropdownMenuButton-three" data-toggle="dropdown" >
                                                <i class="ri-more-fill"></i>
                                            </span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-three" style="">
                                                <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                                                <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                                <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                                <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                                <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="iq-card-body p-0">
                                    <div id="home-chart-03"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Payment History</h4>
                                </div>
                                <div class="iq-card-header-toolbar d-flex align-items-center">
                                    <div class="dropdown">
                                        <span class="dropdown-toggle" id="dropdownMenuButton-four" data-toggle="dropdown" >
                                            <i class="ri-more-fill"></i>
                                        </span>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-four" style="">
                                            <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                                            <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                            <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                            <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                            <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="iq-card-body">
                                <ul class="suggestions-lists m-0 p-0">
                                    <li class="d-flex mb-4 align-items-center">
                                        <div class="profile-icon bg-secondary"><span><i class="ri-refresh-line"></i></span></div>
                                        <div class="media-support-info ml-3">
                                            <h6>Deposit from ATL</h6>
                                            <p class="mb-0">5 march, 18:33</p>
                                        </div>
                                        <div class="media-support-amount ml-3">
                                            <h6 class="text-info">- 1,470</h6>
                                            <p class="mb-0">USD</p>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-4 align-items-center">
                                        <div class="profile-icon bg-primary"><span><i class="ri-paypal-line"></i></span></div>
                                        <div class="media-support-info ml-3">
                                            <h6>Deposit PayPal</h6>
                                            <p class="mb-0">5 march, 18:33</p>
                                        </div>
                                        <div class="media-support-amount ml-3">
                                            <h6 class="text-primary">+ 2,220</h6>
                                            <p class="mb-0">USD</p>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-4 align-items-center">
                                        <div class="profile-icon bg-primary"><span><i class="ri-check-line"></i></span></div>
                                            <div class="media-support-info ml-3">
                                            <h6>Deposit from ATL</h6>
                                            <p class="mb-0">5 march, 18:33</p>
                                        </div>
                                        <div class="media-support-amount ml-3">
                                            <h6 class="text-primary">+ 250</h6>
                                            <p class="mb-0">USD</p>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-4 align-items-center">
                                        <div class="profile-icon bg-info"><span><i class="ri-close-line"></i></span></div>
                                        <div class="media-support-info ml-3">
                                            <h6>Cancelled</h6>
                                            <p class="mb-0">5 march, 18:33</p>
                                        </div>
                                        <div class="media-support-amount ml-3">
                                            <h6 class="text-info">0</h6>
                                            <p class="mb-0">USD</p>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-4 align-items-center">
                                        <div class="profile-icon bg-info"><span><i class="ri-arrow-go-back-fill"></i></span></div>
                                        <div class="media-support-info ml-3">
                                            <h6>Refund</h6>
                                            <p class="mb-0">5 march, 18:33</p>
                                        </div>
                                        <div class="media-support-amount ml-3">
                                            <h6 class="text-info">- 500</h6>
                                            <p class="mb-0">USD</p>
                                        </div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="profile-icon bg-secondary"><span><i class="ri-refresh-line"></i></span></div>
                                        <div class="media-support-info ml-3">
                                            <h6>Deposit from ATL</h6>
                                            <p class="mb-0">5 march, 18:33</p>
                                        </div>
                                        <div class="media-support-amount ml-3">
                                            <h6 class="text-info">- 1,470</h6>
                                            <p class="mb-0">USD</p>
                                        </div>
                                    </li>
                                </ul>
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
