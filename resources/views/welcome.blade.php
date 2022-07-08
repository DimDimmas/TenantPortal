@extends('layouts.crm_main')

@section('content')

<!-- Background image -->
<div id="intro" class="p-5 text-center bg-image shadow-1-strong"
style="background-image: url('https://mdbootstrap.com/img/new/slides/205.jpg'); width: 100%; height: 100%;">
<div class="mask" style="background-color: rgba(0, 0, 0, 0.7);">
  <div class="d-flex justify-content-center align-items-center h-100">
    <div class="text-white px-4" >
      <h1 class="mb-3" style="color:#FFF">Coming Soon!</h1>

      <!-- Time Counter -->
      <h3 id="time-counter" class="border border-light my-4 p-4" style="color:#FFF"></h3>

      <p>We're working hard to finish the development of this site.</p>

    </div>
  </div>
</div>
</div>
<!-- Background image -->

<!-- Time Counter -->
<script type="text/javascript">
    // Set the date we're counting down to
    var countDownDate = new Date();
    countDownDate.setDate(countDownDate.getDate() + 30);

    // Update the count down every 1 second
    var x = setInterval(function () {
      // Get todays date and time
      var now = new Date().getTime();

      // Find the distance between now an the count down date
      var distance = countDownDate - now;

      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);

      // Display the result in the element with id="demo"
      document.getElementById('time-counter').innerHTML =
        days + 'd ' + hours + 'h ' + minutes + 'm ' + seconds + 's ';

      // If the count down is finished, write some text
      if (distance < 0) {
        clearInterval(x);
        document.getElementById('time-counter').innerHTML = 'EXPIRED';
      }
    }, 1000);
  </script>

@endsection