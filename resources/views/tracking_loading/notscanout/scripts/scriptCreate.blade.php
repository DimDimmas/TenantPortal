<script>
  function alerting(title, status, message){
    Swal.fire({
      title: title,
      text: message,
      type: status,
    })
  }
  
  $('#btnSubmit').click((e) => {
    e.preventDefault();

    Swal.fire({
      title: 'Warning!',
      text: 'All inputs is correct?',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
    }).then((result) => {
      if (result.value == true) {
        var data = new FormData();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
        var formData = $('#formRequest').serializeArray();
    
        $.each(formData, function (key, input) {
          data.append(input.name, input.value);
        });
        data.append("_token", CSRF_TOKEN);
        // console.log(data);
        // return false;
    
        $.ajax({
          type: "POST",
          url: "/tracking-loading/not-scan-out/store",
          data: data,
          dataType: "json",
          processData: false,
          contentType: false,
          success: function (result) {
            if(result.status == 'error'){
              alerting('Error!', result.status, result.message );
            }else{
              alerting('Success!', result.status, result.message );
              window.open("/tracking-loading/not-scan-out/print-pdf/?param="+$('#bak_no').val(), "_blank");
              location.href = '/tracking-loading/not-scan-out';
            }
          }
        });
      }
    })
  })
</script>