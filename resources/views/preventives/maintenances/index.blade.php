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
      <h4 class="card-title"><b>Assignment</b></h4>
      </div>
    </div>
    <hr>
    <div class="table-responsive center mt-5">
      <div class="col-xs-auto col-sm-auto col-md-auto col-lg-auto mb-3">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-5 pull-right">          
          <div class="pull-left">
            {{-- <table>              
              <tr>
                <td>Show by Date</td>
                <td></td>
                <td>
                  <input type="text" class="form-control m-2" name="date_filter" id="date_filter">
                </td>
              </tr>
            </table> --}}
          </div>
          <div class="">
            <button type="button" class="btn btn-primary" onclick="shareTask();"><i class="fa-solid fa-share-from-square" aria-hidden="true"></i>&nbsp; Assignment</button>
            <button type="button" class="btn btn-secondary" onclick="openModal();"><i class="fa-solid fa-repeat" aria-hidden="true"></i>&nbsp; Reschedule</button>
            {{-- <button type="button" class="btn btn-success" onclick="window.location.href = '/preventive/maintenances/approval';"><i class="fa-solid fa-handshake" aria-hidden="true"></i>&nbsp; Need Approval</button> --}}
            <button type="button" class="btn btn-success" onclick="refreshCheckListAll();"><i class="fa-solid fa-arrows-rotate" aria-hidden="true"></i>&nbsp; Refresh Checklist</button>
          </div> 
          <div class="clearfix"></div>
        </div>
      </div>
        <div class="table-responsive">
            <table id="table" class="table table-striped table-hover display mb-2" style="color: #353535;">
                <thead>
                <tr>
                    <th scope="col">Id</th>
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
@include("preventives.maintenances.reschedules.modal_list")
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/datetime/1.1.2/js/dataTables.dateTime.min.js"></script>
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

    let table = $("#table").DataTable({
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "order": [[ 7, "ASC" ]],
        "ajax":{
            url: '{{ route("preventive.maintenances.datatable") }}',
            "dataType": "json",
            "type": "POST",
            "data":{ _token: "{{csrf_token()}}"}
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
        let valueInput = event.target.value;
        data = JSON.parse(data);
        $.ajax({
            url: `/bm/preventive-maintenance/transactions/assign-to/${data.id}`,
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
            $("#tblPreventiveUnassignment").DataTable().ajax.reload();
            },
            error: (xhr, ajaxOptions, thrownError) => {
            Swal.fire({
                icon: 'error',
                title: `${xhr.status} : ${xhr.statusText}`,
                text: `${xhr.statusText}`,
            });
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
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "order": [[ 7, "ASC" ]],
        "ajax":{
            url: '{{ route("preventive.maintenances.datatable_reschedule") }}',
            "dataType": "json",
            "type": "POST",
            "data":{ _token: "{{csrf_token()}}"}
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
            $(".assign_to_table").select2({
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
                "visible": false,
            },
            {
                "targets": [1],
                "orderable": false,
                "searchable": false,
                "visible": true,
            },
        ],
        // bDestroy: true,
    });

    // open modal
    function openModal() {
        $(".modal").modal('show');
        tableReschedule.ajax.reload();
    }

  </script>
@endpush