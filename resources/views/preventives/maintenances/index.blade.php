@extends('layouts.crm_main')

@push('other-css')
    <style>
        .scroll-x {
            overflow-x: scroll !important;
            width: 100% !important;
        }
        ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
            color: dark !important;
            opacity: 1 !important; /* Firefox */
        }

        :-ms-input-placeholder { /* Internet Explorer 10-11 */
            color: dark !important;
        }

        ::-ms-input-placeholder { /* Microsoft Edge */
            color: dark !important;
        }
        
        @media only screen and (max-width: 600px) and (max-width: 768px){
            #btnGeneratePdf, #btnGenerateExcel{
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')

<div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
  <div class="iq-card">
    <div class="iq-card-header d-flex justify-content-between">
      <div class="iq-header-title mt-3">
      <h4 class="card-title"><b>Assignment</b></h4>
      </div>
    </div>
    <hr>
    <div class="iq-card-body">
        <div class="d-flex justify-content-start my-3">
            <button type="button" class="btn btn-primary mx-1" onclick="shareTask();"><i class="fa-solid fa-share-from-square" aria-hidden="true"></i>&nbsp; Assignment</button>
            <button type="button" class="btn btn-secondary mx-1" onclick="openModal();"><i class="fa-solid fa-repeat" aria-hidden="true"></i>&nbsp; Reschedule</button>
            {{-- <button type="button" class="btn btn-success" onclick="window.location.href = '/preventive/maintenances/approval';"><i class="fa-solid fa-handshake" aria-hidden="true"></i>&nbsp; Need Approval</button> --}}
            <button type="button" class="btn btn-success mx-1" onclick="refreshCheckListAll();"><i class="fa-solid fa-arrows-rotate" aria-hidden="true"></i>&nbsp; Refresh Checklist</button>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="table" class="table table-striped table-hover display mb-2" style="color: #353535;">
                        <thead>
                        <tr>
                            <th scope="col">Action</th>
                            <th scope="col">Status</th>
                            <th scope="col">Ticket</th>
                            <th scope="col">Location</th>
                            <th scope="col">Barcode</th>
                            <th scope="col">Asset Name</th>
                            <th scope="col">Schedule Date</th>
                            <th scope="col">Assign To</th>
                            <th scope="col">Assign Date</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
@include("preventives.maintenances.reschedules.modal_list")
@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>      
    // Date renderer for DataTables from cdn.datatables.net/plug-ins/1.10.21/dataRender/datetime.js
    $.fn.dataTable.render.moment = function ( from, to, locale ) {
        // Argument shifting
        if ( arguments.length === 1 ) {
            locale = 'en';
            to = from;
            from = 'YYYY-MM-DD';
        }
        else if ( arguments.length === 2 ) {
            locale = 'en';
        }
    
        return function ( d, type, row ) {
            if (! d) {
                return type === 'sort' || type === 'type' ? 0 : d;
            }
    
            var m = window.moment( d, from, locale, true );
    
            // Order and type get a number value from Moment, everything else
            // sees the rendered value
            return m.format( type === 'sort' || type === 'type' ? 'x' : to );
        };
    };

    // $('#table thead tr')
    //   .clone(true)
    //   .addClass('filters')
    //   .appendTo('#table thead');

    let table = $("#table").DataTable({
        "processing": false,
        "serverSide": true,
        "responsive": false,
        "order": [[ 7, "ASC" ]],
        "ajax":{
            url: '{{ route("preventive.maintenances.datatable") }}',
            "dataType": "json",
            "type": "POST",
            "data":{ _token: "{{csrf_token()}}"}
        },
        columns: [
            { 
                data: null, 
                name:'action',
                class: 'text-center',
                width: '5%',
                render: function(data, type, row) {
                    return `
                        <div class="dropdown">
                            <span class="text-dark" id="dropdownMenuButton5" data-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton5">
                                <a class="dropdown-item" href="/preventive/maintenances/${row.id}/check-list"><i class="ri-eye-fill mr-2"></i>View</a>
                                <!-- <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a> -->
                            </div>
                        </div>
                    `;
                }
            },
            { 
                data:'status_name',
                render: function(data, type, row) {
                    switch(row.status){
                        case "1" :
                            classs = "badge-info";
                            break;
                        case "2" :
                            classs = "badge-primary";
                            break;
                        case "4" :
                            classs = "badge-primary";
                            break;
                        case "6" :
                            classs = "badge-success";
                            break;
                        case "15" :
                            classs = "badge-danger";
                        case "16" :
                            classs = "badge-warning";
                            break;
                        case "19" :
                            classs = "badge-danger";
                            break;
                        case "20" :
                            classs = "badge-danger";
                        default:
                            classs = "badge-info";
                    }
                    return `<span class='badge ${classs}'>${row.status_name}</span>`;
                }
            } ,
            { data:'trans_code' },
            { data:'location_name' } ,
            { data:'barcode'} ,
            { data:'asset_name'} ,
            { 
                data:'schedule_date',
                render: $.fn.dataTable.render.moment( 'DD/MM/YYYY' ),
            } ,
            { 
                data:'assign_to',
            } ,
            {
                data: 'assign_date',
                render: $.fn.dataTable.render.moment( 'DD/MM/YYYY' ),
            }
        ],
        drawCallback: function() {
            $(".assign_to_table").select2({
                placeholder: '-- Pilih Teknisi --',
                allowClear: false,
                width: '100%',
            })
        },
        "columnDefs": [
            {
                "targets": [0],
                "orderable": false,
                "searchable": false,
                "visible": true,
            },
        ],
        initComplete: function () {
            var api = this.api();

            // For each column
            api
                .columns()
                .eq(0)
                .each(function (colIdx) {
                // Set the header cell to contain the input element
                var cell = $('#table th').eq(
                    $(api.column(colIdx).header()).index()
                );
                var title = $(cell).text();
                // $(cell).html('<input type="text" placeholder="' + title + '" />');
                // $(cell).html("");
                if(title !== "Action") $(cell).html('<input type="text" placeholder="' + title + '" class="text-dark" />');

                // On every keypress in this input
                $(
                    'input',
                    $('#table th').eq($(api.column(colIdx).header()).index())
                )
                    .off('keyup change')
                    .on('keyup change', function (e) {
                        e.stopPropagation();

                        // Get the search value
                        $(this).attr('title', $(this).val());
                        var regexr = '({search})'; //$(this).parents('th').find('select').val();

                        var cursorPosition = this.selectionStart;
                        // console.log($(this).val());return;
                        // Search the column for that value
                        api
                            .column(colIdx)
                            .search(
                                // this.value != ''
                                //     ? regexr.replace('{search}', '(((' + this.value + ')))')
                                //     : '',
                                // this.value != '',
                                // this.value == ''
                                $(this).val(), false, false, true
                            )
                            .draw();

                        $(this)
                            .focus()[0]
                            .setSelectionRange(cursorPosition, cursorPosition);
                    });
                });
        },
    });

    function shareTask()
    {
        tampilLoader();
        $.ajax({
            url: "{{ route('preventive.maintenance.share_tasks') }}",
            type: 'POST',
            data: {
                "_token": $('meta[name="csrf-token"]').attr('content'),
                "_method": "POST",
            },
            success: (result) => {
                tutupLoader();
                table.ajax.reload();
                Swal.fire({
                    icon: `${result.error ? 'error' : 'success'}`,
                    title: `${result.header}`,
                    text: `${result.message}`,
                    // footer: '<a href="">Why do I have this issue?</a>'
                });
            },
            error: (xhr, ajaxOptions, thrownError) => {
                tutupLoader();
                Swal.fire({
                    icon: 'error',
                    title: `${xhr.status} : ${xhr.statusText}`,
                    text: `${xhr.statusText}`,
                });
            }
        });
    }

    function onChangeAssignTo(event, data)
    {
        let id = event.target.id;
        let valueInput = $(`#${id}`).val();
        // let valueInput = event.target.value;
        data = JSON.parse(data);
        $.ajax({
            url: `/preventive/maintenances/assign-to/${data.id}`,
            type: 'PATCH',
            data: {
                "_token": $('meta[name="csrf-token"]').attr('content'),
                "_method": "PATCH",
                "input": valueInput,
                "old" : data,
            },
            success: (result) => {
                if(result.error)
                {
                    Swal.fire({
                        icon: 'error',
                        title: `${result.header}`,
                        text: `${result.message}`,
                    });
                }
                table.ajax.reload();
                tableReschedule.ajax.reload();
            },
            error: (xhr, ajaxOptions, thrownError) => {
                Swal.fire({
                    icon: 'error',
                    title: `${xhr.statusText}`,
                    text: `${xhr.statusText}`,
                });
                table.ajax.reload();
                tableReschedule.ajax.reload();
            }
        });
    }

    function refreshCheckListAll() {
        tampilLoader();
        $.ajax({
            url: `/preventive/maintenances/refresh-check-list`,
            method: `POST`,
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: "POST",
            },
            success: (result) => {
                console.log("success");
                Swal.fire({
                    icon: 'success',
                    title: `Success`,
                    showCloseButton: true,
                    html: `
                        <h4>
                        Refresh check list done.
                        </h4>
                    `,
                });
                table.ajax.reload();
            },
            error: (xhr, ajaxOptions, thrownError) =>{
                tutupLoader();
                Swal.fire({
                    icon: 'error',
                    title: `${xhr.status} : ${xhr.statusText}`,
                    text: `${xhr.statusText}`,
                });
                table.ajax.reload();
            }
        });
        setTimeout(() => {

            tutupLoader();
            Swal.fire({
            icon: 'info',
            title: `Info`,
            showCloseButton: true,
            html: `
                <h5>
                Please wait, refresh checklist is in progress.
                </h5>
                <h5>
                After the process is complete, there will be a pop up info
                </h5>
            `,
            });

        }, 1000);
        
    }

    function changeScheduleDate(event, data)
    {
        let value = event.target.value;
        let datas = JSON.parse(data);
        let preventiveId = datas.id;
        let trans_code = datas.trans_code;
        $.ajax({
            url: `/preventive/maintenances/reschedule/${preventiveId}`,
            type: 'PATCH',
            data: {
                "_token": $('meta[name="csrf-token"]').attr('content'),
                "_method": "PATCH",
                "preventive_id": parseInt(preventiveId),
                "trans_code" : trans_code,
                "schedule_date" : value,
            },
            success: (result) => {
                if(result.error)
                {
                    Swal.fire({
                        icon: 'error',
                        title: `${result.header}`,
                        text: `${result.message}`,
                    });
                }
                table.ajax.reload();
                tableReschedule.ajax.reload();
            },
            error: (xhr, ajaxOptions, thrownError) => {
                Swal.fire({
                    icon: 'error',
                    title: `${xhr.status} : ${xhr.statusText}`,
                    text: `${xhr.statusText}`,
                });
                table.ajax.reload();
                tableReschedule.ajax.reload();
            }
        });
    }

    let tableReschedule = $("#table-reschedule").DataTable({
        "processing": false,
        "serverSide": true,
        "responsive": false,
        "order": [[ 7, "ASC" ]],
        "ajax":{
            url: '{{ route("preventive.maintenances.datatable_reschedule") }}',
            "dataType": "json",
            "type": "POST",
            "data":{ _token: "{{csrf_token()}}"}
        },
        columns: [
            { 
                data: null, 
                name:'action',
                class: 'text-center',
                width: '5%',
                render: function(data, type, row) {
                    return `
                        <div class="dropdown">
                            <span class="text-dark" id="dropdownMenuButton5" data-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton5">
                                <a class="dropdown-item" href="/preventive/maintenances/${row.id}/check-list"><i class="ri-eye-fill mr-2"></i>View</a>
                                <!-- <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a> -->
                            </div>
                        </div>
                    `;
                }
            },
            { 
                data:'status_name',
                render: function(data, type, row) {
                    switch(row.status){
                        case "1" :
                            classs = "badge-info";
                            break;
                        case "2" :
                            classs = "badge-primary";
                            break;
                        case "4" :
                            classs = "badge-primary";
                            break;
                        case "6" :
                            classs = "badge-success";
                            break;
                        case "15" :
                            classs = "badge-danger";
                        case "16" :
                            classs = "badge-warning";
                            break;
                        case "19" :
                            classs = "badge-danger";
                            break;
                        case "20" :
                            classs = "badge-danger";
                        default:
                            classs = "badge-info";
                    }
                    return `<span class='badge ${classs}'>${row.status_name}</span>`;
                }
            } ,
            { data:'trans_code' },
            { data:'location_name' } ,
            { data:'barcode'} ,
            { data:'asset_name'} ,
            { 
                data:'schedule_date',
                // render: $.fn.dataTable.render.moment( 'DD/MM/YYYY' ),
            } ,
            { 
                data:'assign_to',
            } ,
            {
                data: 'assign_date',
                render: $.fn.dataTable.render.moment( 'DD/MM/YYYY' ),
            }
        ],
        drawCallback: function() {
            $(".assign_to_table_reschedule").select2({
                placeholder: '-- Pilih Teknisi --',
                allowClear: false,
                width: '100%',
            })
        },
        "columnDefs": [
            {
                "targets": [0],
                "orderable": true,
                "searchable": false,
                "visible": true,
            },
        ],
        // bDestroy: true,
        initComplete: function () {
            var api = this.api();

            // For each column
            api
                .columns()
                .eq(0)
                .each(function (colIdx) {
                // Set the header cell to contain the input element
                var cell = $('#table-reschedule th').eq(
                    $(api.column(colIdx).header()).index()
                );
                var title = $(cell).text();
                // $(cell).html('<input type="text" placeholder="' + title + '" />');
                $(cell).html("");
                if(title !== "Action") $(cell).html('<input type="text" placeholder="' + title + '" class="text-dark" />');

                // On every keypress in this input
                $(
                    'input',
                    $('#table-reschedule th').eq($(api.column(colIdx).header()).index())
                )
                    .off('keyup change')
                    .on('keyup change', function (e) {
                        e.stopPropagation();

                        // Get the search value
                        $(this).attr('title', $(this).val());
                        var regexr = '({search})'; //$(this).parents('th').find('select').val();

                        var cursorPosition = this.selectionStart;
                        // Search the column for that value
                        api
                            .column(colIdx)
                            .search(
                                // this.value != ''
                                //     ? regexr.replace('{search}', '(((' + this.value + ')))')
                                //     : '',
                                // this.value != '',
                                // this.value == ''
                                $(this).val(), false, false, true
                            )
                            .draw();

                        $(this)
                            .focus()[0]
                            .setSelectionRange(cursorPosition, cursorPosition);
                    });
                });
        },
    });

    // open modal
    function openModal() {
        $(".modal").modal('show');
        tableReschedule.ajax.reload();
    }

  </script>
@endpush