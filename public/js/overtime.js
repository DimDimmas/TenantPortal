$(document).ready(function(){
    $('.toast').toast('show');

    // $('.select2').select2();

    $('#statusOvertimeBy').on('change', function(e){
        dataTable = $("#tableOvertime").DataTable();
        var status = $(this).val();
        $('#statusBy').val(status)
        console.log(status)
        //dataTable.column(6).search('\\s' + status + '\\s', true, false, true).draw();
        dataTable.column(11).search(status).draw();
    });

    // datepicker show date and day
    $('[data-toggle="datepicker"]').change(function() {
        var weekday=new Array(7);
        weekday[6]="Sunday";
        weekday[0]="Monday";
        weekday[1]="Tuesday";
        weekday[2]="Wednesday";
        weekday[3]="Thursday";
        weekday[4]="Friday";
        weekday[5]="Saturday";
        var a = $(this).datepicker("getDate");
        var aMoment = moment(a).format("YYYY-MM-DD");
        var bMoment = moment(a).format("DD/MM/YYYY");
        var dayOfWeek = weekday[a.getUTCDay()];
        $('#day').val(dayOfWeek);
        $(this).val(bMoment);
        $('#duration').val("");
        $('#end_time').empty();
        $.ajax({
            url: '/overtime/get-time/'+aMoment,
            method: 'GET',
            dataType: 'html',
            success: function (result) {
                $('#start_time').html(result);
            },
        });
    });
    // datepicker show date and day

    // get start time overtime
    $('#start_time').click(function(){
        $('#duration').val("");
        var start = $(this).val();
        console.log(start);
        $.ajax({
            url: '/overtime/get-start-time/'+start,
            method: 'GET',
            dataType: 'html',
            success: function(result){
                $('#end_time').html(result);
            },
        });
    });
    // get start time overtime

    //btn show zone ac rate
    // $('.btn-show').each(function(){
        $(this).on('click', '#btnShowOvertime', function(){
            var code = $(this).data('code');
            // console.log(code);
            $.ajax({
                type : 'get',
                url : '/overtime/history-ticket/get-ovt-details/'+code,
                dataType : 'html',
                success : function (result) { 
                    $('.container_ovt_details').html(result);
                    // console.log(result);
                }, error: function(response) {
                    console.error(response); // for debugging
                }
            });
        })
    // });
    //btn show zone ac rate
    get_zone_value();
    function get_zone_value() { 
        $('select.zone').on('change', function() {
            $('option').prop('disabled', false);
            $('select').each(function() {
                var val = this.value;
                $('select.zone').not(this).find('option').filter(function() {
                    return this.value === val;
                }).prop('disabled', true);
            });
        }).change();
    }
        
    // button add user
    var max_fields      = 10; //maximum input boxes allowed
    var wrapper_user   	= $(".wrap_user"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
    
    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper_user).append('<div class="col-sm-12 mb-3" style="padding-left: 0; margin-top: 10px"><input type="text" class="form-control new-form mr-3" name="user[]" id="user" required><a href="#" class="remove_field_user" style="font-size: 25px;"><i class="fa fa-times-circle" aria-hidden="true" style="color: #d9534f"></i></a><div>'); //add input box
        }
    });
    
    $(wrapper_user).on("click",".remove_field_user", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    });
    // button add user
    
    // button add zone and ac
    var max_fields_zone      = 10; //maximum input boxes allowed
    var wrapper_zone   	= $(".wrap_zone"); //Fields wrapper
    var add_button_zone      = $(".add_row_zoneac"); //Add button ID

    var i = 1; //initlal text box count
    $(add_button_zone).click(function(e){ //on add input button click
        e.preventDefault();
        if(i < max_fields_zone){ //max input box allowed
            i++; //text box increment
            $(wrapper_zone).append('<div id="remove_zone"><label style="margin-top:10px" class="col-sm-2 col-form-label fl-left"></label> <div style="margin-top:10px" class="col-sm-4 fl-left"> <select name="zone[]" id="zone" class="form-control zone" required> <option value="">Choose..</option> <option value="Zone 1">Zone 1</option> <option value="Zone 2">Zone 2</option> <option value="Zone 3">Zone 3</option> <option value="Zone 4">Zone 4</option> <option value="Zone 5">Zone 5</option> <option value="Zone 6">Zone 6</option> <option value="Zone 7">Zone 7</option> <option value="Zone 8">Zone 8</option> <option value="Zone 9">Zone 9</option> <option value="Zone 10">Zone 10</option> <option value="Zone 11">Zone 11</option> </select> </div> <label style="margin-top:10px" class="col-sm-1 col-form-label fl-left"></label> <div style="margin-top:10px" class="col-sm-3 fl-left"> <select class="form-control select2" name="typeAc[]" id="typeAc" oninvalid="this.setCustomValidity("AC not selected.")" onchange="this.setCustomValidity("")" required> <option value="">Choose..</option> <option value="5">Include</option> <option value="6">Exclude</option> </select> </div> <a href="#" class="col-sm-2 remove_field_zone fl-left" style="font-size: 25px; margin-top: 10px;"><i class="fa fa-times-circle" aria-hidden="true" style="color: #d9534f"></i></a> </div>');
            get_zone_value();
        }
    });
    
    $(wrapper_zone).on("click",".remove_field_zone", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); i--;
    });
    // button add zone and ac

    $('#tableOvertime thead .dt-search').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="'+title+'" />' );
    });
 
    // DataTable
    var table = $('#tableOvertime').DataTable({
        ordering: false,
        scrollX: true,
        initComplete: function () {
            this.api().columns().every( function () {
                var that = this;
 
                $('input', this.header()).on( 'keyup change clear', function () {
                    if (that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                });
            });
        }
    });

    $(this).on('click', '#btnSubmit', function(){
        event.preventDefault();
        if($('#date').val() == ''){
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Please fill Date field!'
            });
            return false;
        }
        if($('#zone option:selected').val() == ''){
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Please fill Zone field!'
            });
            return false;
        }
        if($('#typeAc option:selected').val() == ''){
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Please fill AC field!'
            });
            return false;
        }
        if($('#start_time option:selected').val() == ''){
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Please fill Start Time field!'
            });
            return false;
        }
        if($('#end_time option:selected').val() == ''){
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Please fill End Time field!'
            });
            return false;
        }
        if($('#user').val() == ''){
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Please fill User field!'
            });
            return false;
        }

        Swal.fire({
        title: 'Create ticket ?',
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#26B99A',
        cancelButtonColor: '#d33',
        }).then((result) => {
            if (result.value == true) {
            $('#form_overtime').submit();
            }
        })
    })

    $(this).on('click', '#btnCancel', function(){
        event.preventDefault();
        var id = $(this).data('id');
        Swal.fire({
        title: 'Delete ticket ?',
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#d33',
        }).then((result) => {
            if (result.value == true) {
                location.href = "/overtime/history-ticket/delete/"+id;
            }
        })
    })
    
    $(this).on('click', '#btnReqModify', function(){
        var id = $(this).data('id');
        event.preventDefault();
        Swal.fire({
        title: 'Request modify ticket ?',
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#d33',
        }).then((result) => {
            if (result.value == true) {
                $('#form_req_modify'+id).submit();
            }
        })
    })
});

// get duration end time
$(document).on('click', '#end_time', function () { 
    var a = $( "#start_time option:selected" ).val();
    var b = $( "#end_time option:selected" ).val();
    
    // var c = ;
    $.ajax({
        type : 'get',
        url : '/overtime/get-duration/',
        data : {
            'start' : a,
            'end' : b
        },
        success : function(data){
            console.log(data);
            $('#duration').val(data);  // for debugging
        },
        error: function(response) {
            console.error(response); // for debugging
        }
    });
});
// get duration end time
