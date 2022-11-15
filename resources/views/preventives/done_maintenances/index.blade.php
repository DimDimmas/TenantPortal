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
                            <th>Status</th>
                            <th>Total Value</th>
                            <th>Entity</th>
                            <th>Project</th>
                            <th>Location</th>
                            <th>Tenant</th>
                            <th>Barcode</th>
                            <th>Asset Name</th>
                            <th>Actual Date</th>
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
        "order": [[ 9, "ASC" ]],
        "ajax":{
            url: '{{ route("preventive.done_maintenances.datatable") }}',
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
            {data: 'total_value', name: 'total_value'},
            {data: 'entity_name', name: 'entity_name'},
            {data: 'project', name: 'project'},
            {data: 'location_name', name: 'location_name'},
            {data: 'tenant', name: 'tenant'},
            {data: 'barcode', name: 'barcode'},
            {data: 'asset_name', name: 'asset_name'},
            {
                data: 'actual_date',
                render: $.fn.dataTable.render.moment( 'DD/MM/YYYY HH:MM:SS' ),
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

  </script>
@endpush