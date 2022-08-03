<script src="{{ asset('moa/js/jquery.min.js') }}"></script>
<script src="{{ asset('moa/js/popper.min.js') }}"></script>
<script src="{{ asset('moa/js/bootstrap.min.js') }}"></script>

{{-- <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script> --}}

{{-- <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script> --}}

<!-- Appear JavaScript -->
<script src="{{ asset('moa/js/jquery.appear.js') }}"></script>
<!-- Countdown JavaScript -->
<script src="{{ asset('moa/js/countdown.min.js') }}"></script>
<!-- Counterup JavaScript -->
<script src="{{ asset('moa/js/waypoints.min.js') }}"></script>
<script src="{{ asset('moa/js/jquery.counterup.min.js') }}"></script>
<!-- Wow JavaScript -->
<script src="{{ asset('moa/js/wow.min.js') }}"></script>
<!-- Apexcharts JavaScript -->
<script src="{{ asset('moa/js/apexcharts.js') }}"></script>
<!-- Slick JavaScript -->
<script src="{{ asset('moa/js/slick.min.js') }}"></script>
<!-- Select2 JavaScript -->
<script src="{{ asset('moa/js/select2.min.js') }}"></script>
<!-- Owl Carousel JavaScript -->
<script src="{{ asset('moa/js/owl.carousel.min.js') }}"></script>
<!-- Magnific Popup JavaScript -->
<script src="{{ asset('moa/js/jquery.magnific-popup.min.js') }}"></script>
<!-- Smooth Scrollbar JavaScript -->
<script src="{{ asset('moa/js/smooth-scrollbar.js') }}"></script>
<!-- lottie JavaScript -->
<script src="{{ asset('moa/js/lottie.js') }}"></script>
<!-- Chart Custom JavaScript -->
<script src="{{ asset('moa/js/chart-custom.js') }}"></script>
<!-- Custom JavaScript -->
<script src="{{ asset('moa/js/custom.js') }}"></script>
<!-- Custom JavaScript -->
<script src="{{ asset('vendor/jquery-easyui/jquery.easyui.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-easyui/src/datagrid-filter.js') }}"></script>
<!-- ECharts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/canvasjs.js"> </script>


<script src="{{ asset('js/custom_app.js') }}"></script>

<!--select-->
<script src="{{ asset('vendor/select2/dist/js/select2.min.js') }}"></script>

<script src="{{ asset('vendor/jquery-number/jquery.number.js') }}"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}

{{-- DataTables --}}
{{-- <script src="{{ asset('asset/vendors/DataTables-1.10.18/js/jquery.dataTables.min.js') }}" charset="utf-8"></script>
<script src="{{ asset('asset/vendors/DataTables-1.10.18/js/dataTables.bootstrap.min.js') }}" charset="utf-8"></script> --}}

<script src="{{ asset('vendor/DataTables/datatables.js') }}"></script>
<script src="{{ asset('vendor/datePicker/datepicker.js') }}"></script>
<script src="{{ asset('vendor/datePicker/main.js') }}"></script>
<script src="{{ asset('vendor/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>

<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<!-- Timepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

{{-- moment --}}
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
{{-- daterangepicker --}}
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

{{-- toastr --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
    function submitAjax(form_id) {
        $("button[type='submit']").attr('disabled', true);
        var form = $(form_id),
        url = form.attr('action'),
        method = $('input[name=_method]').val() == undefined ? 'POST' : 'PUT';
        message = method == 'POST' ? 'Saved' : 'Updated',
            module_name = form.attr('title');
        formData = new FormData(form[0]);
        form.find('.error').remove();

        $(".form-control").removeClass('is-invalid');
        $(".custom-file-input").removeClass('is-invalid');
        // console.log(url);

        $('.card').find('.error').remove();
        $('table > tbody  > tr').each(function(index, tr) {

            console.log(index);
            $('table tbody tr').eq(index).removeClass('red');

        });

        $.ajax({
            url: url,
            method: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            data: formData,
            success: function (response) {
                $("button[type='submit']").attr('disabled', false);
                
                if (response.error == false) {
                    if(response.code == 201 || response.code == 200){
                        form.trigger('reset');
                        $(".select2").val('').trigger('change');
                        $("#baris_preview_img").empty();

                        $('#table').DataTable().ajax.reload();
                        toastr.success(response.message);
                        $(".modal").modal("hide");

                    }

                    // $('#table').DataTable().ajax.reload();
                    // toastr.success(response.message);
                    // $(".modal").modal("hide");
                }else{
                    if(response.code == 422){
                        toastr.error(response.message);
                        $.each(response.errors, function (key, value) {
                            $('#' + key)
                                .closest('.form-control')
                                .addClass('is-invalid')
                            $('#' + key)
                                .closest('.form-group')
                                .append('<span class="error invalid-feedback">' + value + '</span>')
                        })
                    }
                    // Untuk validasi row table
                    else if(response.code == 423){
                        toastr.error(response.message);

                        $.each(response.errors, function (key, value) {
                            $('#' + key)
                                .closest('.form-control')
                                .addClass('is-invalid')
                            $('#' + key)
                                .closest('.form-group')
                                .append('<span class="error invalid-feedback">' + value + '</span>')



                            row_nya = key.split("_");


                            if(row_nya){
                                $('table tbody tr').eq(row_nya[1]).addClass('red');
                            }

                        });



                    }else{
                        toastr.error(response.message);
                        // console.log(response.message);
                    }
                }
                $("button[type='submit']").attr('disabled', false);
            }, 
            error: (xhr, ajaxOptions, thrownError) => {
                $("button[type='submit']").attr('disabled', false);
                toastr.error(thrownError);
            }
        })
    }
</script>