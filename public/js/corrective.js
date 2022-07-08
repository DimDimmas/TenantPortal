$(document).ready(function(){
    $('.toast').toast('show');
    // call select2
    $('.select2').select2({
        allowClear: true,
        placeholder: "-",
    });
    // call select2

    $('[data-toggle="tooltip"]').tooltip()      
    
    $('#statusBy').on('change', function(e){
        dataTable = $("#tableRequestTicket").DataTable();
        var status = $(this).val();
        $('#statusBy').val(status)
        console.log(status)
        //dataTable.column(6).search('\\s' + status + '\\s', true, false, true).draw();
        dataTable.column(3).search(status).draw();
    });

    $('#tableRequestTicket thead .dt-search').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="'+title+'" />' );
    } );
 
    // DataTable
    var table = $('#tableRequestTicket').DataTable({
        ordering: false,
        scrollX: true,
        initComplete: function () {
            this.api().columns().every( function () {
                var that = this;
 
                $( 'input', this.header() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            } );
        }
    });

    $(this).on('click', '#btnSubmit', function () { 
        event.preventDefault();
        if($('#formId').val() == ''){
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Form not selected!'
            });
            return false;
        }
        if($('#location').val() == ''){
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Please fill location field!'
            });
            return false;
        }
        if($('#description').val() == ''){
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Please fill description field!'
            });
            return false;
        }
        if($('#tenantTicketAttachment').get(0).files.length === 0){
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Please insert attachment!'
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
            $('#form_corrective').submit();
            }
        })
    })
    
    $(this).on('click', '#btnDelete', function () { 
        var id = $(this).data('id');
        console.log(id);
        Swal.fire({
        title: 'Delete this ticket ?',
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#d33',
        }).then((result) => {
            if (result.value == true) {
                location.href = "/corrective/history-ticket/delete/"+id;
            }
        })
    })

    $(this).on('click', '#btnClose', function () { 
        var id = $(this).data('id');
        console.log(id);
        Swal.fire({
        title: 'Close this ticket ?',
        text: 'make sure if the complaint is finished',
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#27b345',
        cancelButtonColor: '#d33',
        }).then((result) => {
            if (result.value == true) {
                $('#form_close').submit();
            }
        })
    })
});
    
$(document).on('click', '#btnModalImg', function () { 
    var attachment = $(this).data('attach');

    $('#post-attach').attr('src', '/img/bms/photo/'+attachment);
});
$(document).on('click', '#btnModalConfirm', function(){
    var ticketid = $(this).data('status');
    $('#post-idclose').text(ticketid);
    $('#confirmModal').modal('show');
});
$(document).on('click', '#btnModalShow', function () { 
    var ticketid = $(this).data('ticketid');
    var status = $(this).data('status');
    var formname = $(this).data('formname');
    var category = $(this).data('category');
    var typename = $(this).data('typename');
    var companyname = $(this).data('companyname');
    var location = $(this).data('location');
    var description = $(this).data('description');
    var date = $(this).data('date');
    var attach = $(this).data('attch');

    $('#post-id').val(ticketid);
    $('#post-status').val(status);
    $('#post-form').val(formname);
    $('#post-category').val(category);
    $('#post-type').val(typename);
    $('#post-company_name').val(companyname);
    $('#post-location').val(location);
    $('#post-description').val(description);
    $('#post-date').val(date);
    $('#post-img').attr('src', '/img/bms/photo/'+attach);        
});

// call select2
// $(document).ready(function () {
       var uri0 = '/corrective/request-ticket/get-category-id/';
//     var uri1 = '/corrective/ajax/get_type_ticket';
//     var uri1 = '/corrective/ajax/get_cate_ticket';
jQuery('#formId').on('select2:select', function(){
    // console.log('test');
    select2_select ('cateId', uri0, 'formId', '', '', '', true);
    allowClear: true
});
// call select2