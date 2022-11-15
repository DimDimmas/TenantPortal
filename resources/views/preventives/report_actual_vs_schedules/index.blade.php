@extends('layouts.crm_main')

@push('other-css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.1.2/css/dataTables.dateTime.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.1.0/css/fixedColumns.dataTables.min.css">
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
      <h4 class="card-title"><b>Report Actual VS Schedules</b></h4>
      </div>
    </div>
    <hr>
    <div class="iq-card-body">
      <div class="row" style="margin-top:10px;margin-bottom:10px;margin-left:10px;">
        <table width=50% cellpadding="8" style="border: 0 solid #fff">
          <thead>
            <tr>
              <th colspan="3" class="text-justify"><h5 style="font-weight:bolder !important;">Description</h5></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td width="10%" class="text-justify"><p style="font-weight:bolder !important;color:#03fc39;">D</p></td>
              <td width="5%" class="text-justify"><p style="font-weight:bolder !important;color:#03fc39;">:</p></td>
              <td width="85%" class="text-justify"><p style="font-weight:bolder !important;color:#03fc39;">Day</p></td>
            </tr>
            <tr>
              <td width="10%" class="text-justify"><p style="font-weight:bolder !important;color:#0345fc;">W</p></td>
              <td width="5%" class="text-justify"><p style="font-weight:bolder !important;color:#0345fc;">:</p></td>
              <td width="85%" class="text-justify"><p style="font-weight:bolder !important;color:#0345fc;">Week</p></td>
            </tr>
            <tr>
              <td width="10%" class="text-justify"><p style="font-weight:bolder !important;color:#f29202;">M</p></td>
              <td width="5%" class="text-justify"><p style="font-weight:bolder !important;color:#f29202;">:</p></td>
              <td width="85%" class="text-justify"><p style="font-weight:bolder !important;color:#f29202;">Month</p></td>
            </tr>
            <tr>
              <td width="10%" class="text-justify"><p style="font-weight:bolder !important;color:#ff03ea;">A</p></td>
              <td width="5%" class="text-justify"><p style="font-weight:bolder !important;color:#ff03ea;">:</p></td>
              <td width="85%" class="text-justify"><p style="font-weight:bolder !important;color:#ff03ea;">Actual</p></td>
            </tr>
          </tbody>
        </table>
        <hr>
      </div>

      <div class="row" style="margin-top:10px;margin-bottom:20px;">
        {{-- <div class="col-sm-12 col-md-3"> --}}
          <input type="hidden" name="keyword" id="keyword" class="form-control" placeholder="Cari Berdasarkan....">
        {{-- </div> --}}
        {{-- <div class="col-sm-12 col-md-3">
            <select class="form-control form-control-sm select2" name="entity_project" id="entity_project"></select>
        </div> --}}
        <div class="col-sm-12 col-md-3">
            <input type="text" name="date" class="form-control date-picker" value="{{ date('m/Y') }}" placeholder="Pilih Waktu ex : MM/YYYY">
        </div>
        <div class="col-sm-12 col-md-6">
            <button type="button" id="filter" name="filter" class="btn btn-primary">Filter</button>
            <button type="button" id="exportExcel" name="export_excel" class="btn btn-success">Export Excel</button>
        </div>
      </div>

      <div class="table-responsive">
        <table id="table" class="table table-striped table-bordered display mb-2" style="color: #353535;">
            <thead>
                <tr>
                    {{-- <th scope="col" rowspan="2" width="15%">Location</th>
                    <th scope="col" rowspan="2" width="10%">Tenant</th> --}}
                    <th scope="col" rowspan="2" width="15%" style="background-color: rgba(53, 53, 53, 0.919);color:whitesmoke;">Asset Name</th>
                    <th scope="col" class="text-center" id="thMonth1" width="30%">Date</th>
                    <th scope="col" class="text-center" id="thMonth2" width="30%">Date</th>
                </tr>
                <tr id="trDate"></tr>
                <tbody id="tbodyData"></tbody>
                <tfoot></tfoot>
            </thead>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/datetime/1.1.2/js/dataTables.dateTime.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script> 
$('.date-picker').datepicker( {
    icons: {
      time: "fa fa-clock-o",
      date: "fa fa-calendar",
      up: "fa fa-arrow-up",
      down: "fa fa-arrow-down"
    },
    leftArrow: '&laquo;',
    rightArrow: '&raquo;',
    showClose: true,
    inline: true,
    sideBySide: true,
    viewMode: 'years',
    format: 'MM/YYYY',
    toggleActive: true,
});


function getAllData(date, keyword) {
  
  $.ajax({
    url: "{{ route('preventive.report_actual_vs_schedules.table_index') }}",
    method: `POST`,
    data: {
      _token: $('meta[name="csrf-token"]').attr('content'),
      _method: "POST",
      "entity_project" : '{{ auth()->user()->entity_project }}',
      "date" : date,
      "keyword" : keyword,
    },
    success: (result) => {
        let catKaki = 0;

        for(let k = 0; k < result.descs.length; k++) {
            catKaki++;
            $("#thMonth" + catKaki).attr("colspan", parseInt(result.total_days[k]));
            $("#thMonth" + catKaki).text(result.descs[k]);
        }

        let htmlTrDate = '';
        
        for(let i = 0; i < result.total_days.length; i++) {
            for(let j = 1; j <= result.total_days[i]; j++) {
            htmlTrDate += `<th>${j}</th>`;
            }
        }
        $("#trDate").html(htmlTrDate);
        
        let htmlData = ``;
        // let data = result.data;
        $("#tbodyData").html('');
        
        $("#tbodyData").html(result.html);
        let table = $("#table").DataTable({
            bDestroy : true,
            paging : true,
            responsive: false,
            scrollY:        "300px",
            scrollX:        true,
            scrollCollapse: true,
            fixedColumns:   {
              left: 1,
              right: 0
            }
        });
        table.page(1);
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
getAllData("{{ date('m/Y') }}", $("#keyword").val());

$("#filter").on("click", function(e) {
  $("#table").DataTable().clear().destroy();
  $("#thMonth1").attr("colspan", 1);
  $("#thMonth1").text("Date");
  $("#thMonth2").attr("colspan", 1);
  $("#thMonth2").text("Date");
  $("#trDate").html('');
  $("#trDate").html('');
  $("#tbodyData").html('');
  getAllData($("input[name='date']").val(), $("#keyword").val());
});

$("#exportExcel").on("click", function() {
  let entity_project = $("#entity_project").val() ? $("#entity_project").val() : null;
  let date = $("input[name='date']").val() ? btoa($("input[name='date']").val()) : null;
  let query = ``;

  query += `?date=${date}`;

  if(entity_project != null) {
    query += `&entity_project={{ auth()->user()->entity_project }}`;
  }

  window.location.href = `/preventive/report-actual-vs-schedule/excel` + query;
});

$("#keyword").on("keyup", function() {
  getAllData($("input[name='date']").val(), $("#keyword").val());
})
</script>
@endpush