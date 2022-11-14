@extends('layouts.crm_main')

@push('other-css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.1.2/css/dataTables.dateTime.min.css">
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
        <div class="table-responsive center mt-5">
            <div class="col-xs-auto col-sm-auto col-md-auto col-lg-auto mb-3">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-5 pull-right">          
                <div class="pull-left"></div>
                <div class="clearfix"></div>
            </div>
            </div>
            <div class="table-responsive">
            <table id="table" class="table table-striped table-bordered table-hover display mb-2" style="color: #353535;">
                <thead>
                    <tr>
                    <th scope="col">Id</th>
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
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/datetime/1.1.2/js/dataTables.dateTime.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>  
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
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "order": [[ 0, "ASC" ]],
        "ajax":{
            url: '{{ route("datatable_check_list.index", $preventive->id) }}',
            "dataType": "json",
            "type": "POST",
            "data":{ _token: "{{csrf_token()}}", _method: "POST"}
        },
        columns: [
            { data:'id' } ,
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

    function openModal(id) {
        $(".modal").modal('show');
        $("#table-check-standard").DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
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

  </script>
@endpush