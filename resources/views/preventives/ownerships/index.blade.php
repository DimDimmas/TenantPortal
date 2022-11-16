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

<div class="col-sm-12">

    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                  <div class="iq-header-title mt-3">
                    <h4 class="card-title"><b>Ownerships</b></h4>
                  </div>
                </div>
                <hr>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table id="table" class="table table-bordered table-striped table-hover" style="color: #353535;">
                                    <thead>
                                    <tr>
                                        <th scope="col">Action</th>
                                        <th>Asset Name</th>
                                        <th>Barcode</th>
                                        <th>Location</th>
                                        <th>Location Room</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @include("preventives.ownerships.schedules.modal_list")
        </div>
    </div>
</div>
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
        "order": [[ 2, "ASC" ]],
        "ajax":{
            url: '{{ route("preventive.ownerships.datatable") }}',
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
                                <a class="dropdown-item" href="javascript:void(0);" onclick="openModal('${row.entity_project}', '${row.project_code}', ${row.asset_detail_id}, '${row.asset_name}');"><i class="fa-regular fa-calendar-days mr-2"></i>Schedule</a>
                                <!-- <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a> -->
                            </div>
                        </div>
                    `;
                }
            },
            { data:'asset_name' } ,
            { data:'barcode' },
            { data:'location'} ,
            { data:'location_room'} ,
        ],
        // drawCallback: function() {
        //     $(".assign_to_table").select2({
        //         placeholder: '-- Pilih Teknisi --',
        //         allowClear: false,
        //         width: '100%',
        //     })
        // },
        "columnDefs": [
            {
                "targets": [2],
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
                if(title !== "Action") $(cell).html('<input type="text" placeholder="' + title + '" class="text-dark" style="width: 100%;" />');
                
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

    function dataTableSchedules(entity_project, project_code, asset_detail_id) {
        $("#table-schedules").DataTable({
            "processing": false,
            "serverSide": true,
            "responsive": false,
            "order": [[ 2, "ASC" ]],
            "ajax":{
                url: '{{ route("preventive.ownerships.datatable_schedules") }}',
                "dataType": "json",
                "type": "POST",
                "data":{
                     _token: "{{csrf_token()}}",
                     entity_project : entity_project,
                     project_code : project_code,
                     asset_detail_id : asset_detail_id,
                }
            },
            columns: [
                {data: 'asset_name', name: 'asset_name'},
                {data: 'barcode', name: 'barcode'},
                {
                    data: 'pm_schedule_date', 
                    name: 'pm_schedule_date',
                    "width": "5%",
                    render : function ( data, type, row, meta ) {
                        let d = JSON.stringify(row);
                        return `<input type='date' class='text-secondary form-control' style='font-size:9pt;' placeholder='Schedule Date'
                            name='schedule_date${row.id}' id='schedule_date${row.id}'
                            value='${row.pm_schedule_date}'
                            onchange='changeScheduleDate(event, ${d});'
                        />`;
                    },
                    orderable: true
                },
                {data: 'is_submit', name: 'is_submit', className: 'text-center', orderable: false, searchable: false, "width": "5%"},
            ],
            bDestroy : true,
            initComplete: function () {
                var api = this.api();

                // For each column
                api
                    .columns()
                    .eq(0)
                    .each(function (colIdx) {
                    // Set the header cell to contain the input element
                    var cell = $('#table-schedules th').eq(
                        $(api.column(colIdx).header()).index()
                    );
                    var title = $(cell).text();
                    // $(cell).html('<input type="text" placeholder="' + title + '" />');
                    // $(cell).html("");
                    if(title !== "On Transaction") $(cell).html('<input type="text" placeholder="' + title + '" class="text-dark" style="width: 100%;" />');
                    
                    // On every keypress in this input
                    $(
                        'input',
                        $('#table-schedules th').eq($(api.column(colIdx).header()).index())
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
    }

    function openModal(entity_project, project_code, asset_detail_id, asset_name) {
        $("#staticBackdropLabel").text("Schedule Asset " + asset_name);
        $("#modal-schedules").modal("show");
        dataTableSchedules(entity_project, project_code, asset_detail_id);
    }

    function changeScheduleDate(event, data) {
        let value = event.target.value;
        $.ajax({
            url: `/preventive/ownerships/schedules/change-schedule-date/${data['id']}`,
            method: `POST`,
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: "PATCH",
                id: data['id'],
                pm_schedule_date: value,
            },
            success: (result) => {

                if(result.error) {
                    Swal.fire({
                        icon: `${result.error ? 'error' : 'success'}`,
                        title: `${result.header}`,
                        text: `${result.message}`,
                        // footer: '<a href="">Why do I have this issue?</a>'
                    });
                }
                
                $("#tblPmScheduleAsset").DataTable().ajax.reload();
            },
            error: (xhr, ajaxOptions, thrownError) =>{
                Swal.fire({
                    icon: 'error',
                    title: `${xhr.status} : ${xhr.statusText}`,
                    text: `${xhr.statusText}`,
                });
            }
        });
    }

  </script>
@endpush