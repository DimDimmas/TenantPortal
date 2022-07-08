<!doctype html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      @yield('title')
      @yield('stylesheet')
   </head>
   <body>
      <!-- loader Start -->
      <div id="loading">
         <div id="loading-center">
         </div>
      </div>
      <!-- loader END -->  
      <!-- Wrapper Start -->
      
      <!-- Sidebar  -->
      @include('layouts.moa_sidebar')
      <!-- TOP Nav Bar -->
     @include('layouts.moa_topnavbar')
      <!-- TOP Nav Bar END -->
      <!-- Responsive Breadcrumb End-->
         <!-- Page Content  -->      
      
         @yield('content')
               </div>
           
      <!-- Wrapper END -->
      <!-- Footer -->
      @include('layouts.moa_footer')
      <!-- Footer END -->
      <!-- Optional JavaScript -->
      @yield('javascript')
      
   </body>
</html>
