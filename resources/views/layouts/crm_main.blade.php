@include('layouts.crm_header');
     
<!-- TOP Nav Bar -->
@include('layouts.crm_topnavbar2')
<!-- TOP Nav Bar END -->
   <!-- Page Content  -->      
   <div id="content-page" class="content-page">
      <div class="container">
         <div class="row">
            @yield('content')
         </div>
      </div>
   </div>
      

<!-- Footer -->
@include('layouts.crm_footer')
<!-- Footer END -->

@stack('scripts')


</body>
</html>

