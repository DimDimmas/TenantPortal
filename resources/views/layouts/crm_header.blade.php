<!doctype html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>Customer Relation Management v1.0b</title>
      <!-- Favicon -->
      <link rel="shortcut icon" href="{{ asset('moa/images/favicon.png')}}" />
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="{{ asset ('moa/css/bootstrap.min.css')}}">
      <!-- Typography CSS -->
      <link rel="stylesheet" href="{{ asset ('moa/css/typography.css')}}">
      <!-- Style CSS -->
      <link rel="stylesheet" href="{{ asset ('moa/css/style.css')}}">
      <!-- Responsive CSS -->
      <link rel="stylesheet" href="{{ asset ('moa/css/responsive.css')}}">
      <!-- DataTable -->
      {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css"> --}}
       <!-- Full calendar -->
      <link rel="stylesheet" href='{{ asset ('moa/fullcalendar/core/main.css')}}' rel='stylesheet' />
      <link rel="stylesheet" href='{{ asset ('moa/fullcalendar/daygrid/main.css')}}' rel='stylesheet' />
      <link rel="stylesheet" href='{{ asset ('moa/fullcalendar/timegrid/main.css')}}' rel='stylesheet' />
      <link rel="stylesheet" href='{{ asset ('moa/fullcalendar/list/main.css')}}' rel='stylesheet' />
      <!-- flatpickr CSS 
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> -->
      <link rel="stylesheet" href="{{ asset ('moa/css/flatpickr.min.css')}}">
      <!-- jquery easyui -->
      <link rel="stylesheet" href="{{ asset ('vendor/jquery-easyui/themes/bootstrap/easyui.css')}}" />
      <link rel="stylesheet" href="{{ asset ('vendor/jquery-easyui/themes/icon.css')}}" />
      <link rel="stylesheet" href="{{ asset ('vendor/jquery-easyui/themes/color.css')}}" />
       <!-- Style CSS -->
       <link rel="stylesheet" href="{{ asset ('moa/css/custom-style.css')}}">
      <!-- select2 -->
      <link rel="stylesheet" href="{{ asset ('vendor/select2/dist/css/select2.min.css')}}" />
      {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
      {{-- DataTables --}}
      <link rel="stylesheet" href="{{ asset ('vendor/DataTables/datatables.css')}}" />
      <link rel="stylesheet" href="{{ asset ('vendor/datePicker/datepicker.css')}}" />

      <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

      <!-- Loading -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css">

      <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

      {{-- toastr --}}
      <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

      <style>
         /* .top-tab-horizontal .content-page { padding: 30px 0px !important} */
         .navbar-list li:last-child > a > i { font-size: 3rem; }
         .logo > img { max-width: 40vh; max-height: 8vh; max-width: 145px !important; max-height: 40px !important;}
         /* .datagrid-header .datagrid-cell span, .datagrid-header div.datagrid-cell-group { font-weight: bold; } */
         /* .input-group-addon { background: #999; padding: 0 10px; color: #FFF; }		 */
         /* .input-daterange .textbox{ -webkit-border-radius:0 !important; border-radius:0 !important;} */
         /* .iq-menu-horizontal .iq-sidebar-menu .iq-menu li a { padding: 30px 10px 28px 0; } */
         /* .iq-sidebar-logo { width:10%;} */
         body { background-color: #efeefd !important;}
        .iq-top-navbar{
          left: 0px !important;
          right: 0px !important;
          padding: 0px !important;
        }
        .iq-navbar-custom{
          margin: 0px !important;
          border-radius: 0px;
        }
      </style>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" 
         integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" 
         crossorigin="anonymous" referrerpolicy="no-referrer" 
      />

   </head>
   <body class="iq-page-menu-horizontal">
      <!-- loader Start -->
      <div id="loading">
         <div id="loading-center">
         </div>
      </div>
      <!-- loader END -->
      <!-- Wrapper Start -->
      <div class="wrapper">   