@extends('layouts.crm_main')

@push('other-css')
    <style>
        .btn{
            border-radius: 0px;
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
            <h4 class="card-title"><b>CHECK LIST ({{ $preventive->trans_code }})</b></h4>
            </div>
        </div>
        <hr>
        <div class="iq-card-body">
            <div class="d-flex justify-content-start my-3">
                <button class="btn btn-warning mx-1" onclick="history.back();">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back
                </button>
                <button class="btn btn-success mx-1" onclick="openModalHistory();">
                    <i class="fa-solid fa-clock-rotate-left" aria-hidden="true"></i> History
                </button>
            </div>
            <div class="table-responsive">
                <table id="table" class="table table-striped table-bordered table-hover display mb-2" style="color: #353535;">
                    <thead>
                        <tr>
                            <th scope="col">Action</th>
                            <th scope="col">Status</th>
                            <th scope="col">Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Value</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@include("preventives.check_standards.modal_datatable_by_check_list_id")
@include("preventives.maintenances.histories.modal_list")
@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script> 
    let trans_code = '{{ $preventive->trans_code }}';
    toastr.options = {
      "closeButton" : true,
      "progressBar" : true
    }
    
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

    let table = $("#table").DataTable({
        "processing": false,
        "serverSide": true,
        "responsive": false,
        "order": [[ 2, "ASC" ]],
        "ajax":{
            url: '{{ route("datatable_check_list.index", $preventive->id) }}',
            "dataType": "json",
            "type": "POST",
            "data":{ _token: "{{csrf_token()}}", _method: "POST"}
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
                                <a class="dropdown-item" href="javascript:void(0);" onclick="openModal('${row.id}');"><i class="ri-eye-fill mr-2"></i>View</a>
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
                        case "13" :
                            classs = "badge-success";
                            break;
                        case "18" :
                            classs = "badge-warning";
                            break;
                        case "14" :
                            classs = "badge-danger";
                            break;
                        default:
                            classs = "badge-info";
                    }
                    return row.status ? `<span class='badge ${classs}'>${row.status_name}</span>` : `<span class='badge ${classs}'>New</span>`;
                }
            } ,
            { data:'check_list_name' },
            { data:'check_list_description' } ,
            { data:'value'} ,
        ],
        "columnDefs": [
            {
                "targets": [0],
                "orderable": true,
                "searchable": false,
                "visible": true,
            },
            // {
            //     "targets": [1],
            //     "orderable": false,
            //     "searchable": false,
            //     "visible": true,
            // },
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

    function openModal(id) {
        $("#modal-checkstandard").modal('show');
        $("#table-check-standard").DataTable({
            "processing": false,
            "serverSide": true,
            "responsive": false,
            "order": [[ 0, "ASC" ]],
            bDestroy: true,
            "ajax":{
                url: `/preventive/maintenances/check-standard/${id}`,
                "dataType": "json",
                "type": "POST",
                "data":{ _token: "{{csrf_token()}}", _method: "POST"}
            },
            columns: [
                { data:'id' } ,
                { data:'task_list_detail_name' },
                { data:'task_list_detail_description' } ,
                { 
                    data:'status_name',
                    className : 'text-center',
                    render: function(data, type, row) {
                        switch(row.status){
                            case "13" :
                                classs = "badge-success";
                                break;
                            case "18" :
                                classs = "badge-warning";
                                break;
                            case "14" :
                                classs = "badge-danger";
                                break;
                            default:
                                classs = "badge-info";
                        }
                        return row.status ? `<span class='badge ${classs}'>${row.status_name}</span>` : `<span class='badge ${classs}'>New</span>`;
                    }
                } ,
                { data:'value'} ,
                { 
                    data:'is_required',
                    orderable: false,
                    searchable: false,
                    className : 'text-center',
                    render : function(data, type, row) {
                        let is_required = row.is_required ?? null;
                        let classs = '';
                        switch(is_required){
                            case 1 :
                                classs = `<i class="fa-solid fa-check text-success" aria-hidden="true"></i>`;
                                break;
                            case 0 :
                                classs = `<i class="fa-solid fa-xmark text-danger" aria-hidden="true"></i>`;
                                break;
                            default:
                                classs = `<i class="fa-solid fa-xmark text-danger" aria-hidden="true"></i>`;
                        }
                        return classs;
                    }
                } ,
                { 
                    data:'image_required',
                    orderable: false,
                    searchable: false,
                    className : 'text-center',
                    render : function(data, type, row) {
                        let classs = '';
                        switch(row.image_required){
                            case 1 :
                                classs = `<i class="fa-solid fa-check text-success" aria-hidden="true"></i>`;
                                break;
                            case 0 :
                                classs = `<i class="fa-solid fa-xmark text-danger" aria-hidden="true"></i>`;
                                break;
                            default:
                                classs = `<i class="fa-solid fa-xmark text-danger" aria-hidden="true"></i>`;
                        }
                        return classs;
                    }
                } ,
                { 
                    data:'video_required',
                    orderable: false,
                    searchable: false,
                    className : 'text-center',
                    render : function(data, type, row) {
                        let classs = '';
                        switch(row.video_required){
                            case 1 :
                                classs = `<i class="fa-solid fa-check text-success" aria-hidden="true"></i>`;
                                break;
                            case 0 :
                                classs = `<i class="fa-solid fa-xmark text-danger" aria-hidden="true"></i>`;
                                break;
                            default:
                                classs = `<i class="fa-solid fa-xmark text-danger" aria-hidden="true"></i>`;
                        }
                        return classs;
                    }
                } ,
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "orderable": true,
                    "searchable": false,
                    "visible": false,
                },
                {
                    "targets": [1],
                    "orderable": false,
                    "searchable": false,
                    "visible": true,
                },
            ]
        });
    }

    function openModalHistory() {
        $("#modal-histories").modal("show");
        $("#table-histories").DataTable({
            order: [[6, 'DESC']],
            responsive: false,
            processing: false,
            serverSide: true,
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50, 100],
            ajax: {
                url: `{{ route('preventive.maintenances.datatable_histories', $preventive->trans_code) }}`,
                type: 'POST',
                data: {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    "_method": "POST",
                    "preventiveId" : trans_code,
                    "pm_asset_detail_id" : "{{ $preventive->pm_asset_detail_id }}"
                },
            },
            columns: [
                {data: 'trans_code', name: 'trans_code', },
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
                {data: 'location_name', name: 'location_name'},
                {data: 'barcode', name: 'barcode'},
                {data: 'asset_name', name: 'asset_name'},
                { 
                    data:'schedule_date',
                    render: $.fn.dataTable.render.moment( 'DD/MM/YYYY' ),
                } ,
                {data: 'emp_name', name: 'emp_name'},
                
                {
                    data: 'assign_date', 
                    render: $.fn.dataTable.render.moment( 'DD/MM/YYYY' ),
                },
            ],
            "columnDefs": [
                {
                    "targets": [ 0 ],
                    "orderable": false,
                    "searchable": false
                },
            ],
            bDestroy: true, // for destroy datatable and call it again,
            initComplete: function () {
                var api = this.api();

                // For each column
                api
                    .columns()
                    .eq(0)
                    .each(function (colIdx) {
                    // Set the header cell to contain the input element
                    var cell = $('#table-histories th').eq(
                        $(api.column(colIdx).header()).index()
                    );
                    var title = $(cell).text();
                    // $(cell).html('<input type="text" placeholder="' + title + '" />');
                    // $(cell).html("");
                    if(title !== "Action") $(cell).html('<input type="text" placeholder="' + title + '" class="text-dark" />');

                    // On every keypress in this input
                    $(
                        'input',
                        $('#table-histories th').eq($(api.column(colIdx).header()).index())
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

  </script>
@endpush