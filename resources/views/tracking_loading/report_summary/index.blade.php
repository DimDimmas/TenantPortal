@extends('layouts.crm_main')

@section('content')
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
<div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
  <div class="iq-card">
    <div class="iq-card-header d-flex justify-content-between">
      <div class="iq-header-title mt-3">
      <h4 class="card-title"><b>Tracking Loading Report Summary - <i>{{ $tenant->company_name }}</i></b></h4>
      </div>
    </div>
    <hr>
    <div class="table-responsive center mt-5">
      <div class="col-xs-auto col-sm-auto col-md-auto col-lg-auto mb-3">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-5 pull-right">          
          <div class="pull-left">
            <table>              
              <tr>
                <td>Show by Date</td>
                <td></td>
                <td>
                  <input type="text" class="form-control m-2" name="date_filter" id="date_filter">
                </td>
              </tr>
            </table>
          </div>
          <div class="pull-right">
            {{-- <button class="btn btn-danger" id="btnGeneratePdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> &nbsp; Generate PDF</button>
            <button class="btn btn-success" id="btnGenerateExcel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> &nbsp; Generate Excel</button> --}}
          </div>
        </div>
        <div class="table-responsive">
          <table id="tableList" class="mb-2 table table-sm table-bordered" style="color: #353535">
            <thead>
              <tr>
                {{-- <th rowspan="2">Entity</th>
                <th rowspan="2">Tenant Name</th> --}}
                <th rowspan="2">Date</th>
                <th rowspan="2">Day</th>
                <th rowspan="2" style="text-align: center;">Count of Vehicle</th>
                <th rowspan="1" colspan="3" style="text-align: center;">Duration in Warehouse (Hour)</th>
                <th rowspan="2" style="text-align: center;">Not Yet Scan Out</th>
              </tr>
              <tr>
                <th style="text-align: center;">Average</th>
                <th style="text-align: center;">Min</th>
                <th style="text-align: center;">Max</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script>
function getDataTable(dateSelected){
    $('#tableList').DataTable({
      order: [[0, 'desc']],
      dom: 'Bfrtip',
      buttons: [
          // { extend: 'copyHtml5', text: 'Copy', className: 'btn btn-sm btn-info' },
          { extend : 'excelHtml5', text: 'Excell', className: 'btn btn-sm btn-success' },
          // { extend : 'csvHtml5', text: 'CSV', className: 'btn btn-sm btn-success' },
          { extend : 'pdfHtml5', text: 'PDF', className: 'btn btn-sm btn-danger' }
      ],
      processing: true,
      serverSide: true,
      ordering: false,
      ajax: {
        url: '/tracking-loading/report-summary/get-data-table',
        method: 'GET',
        data: {dateSelected:dateSelected}
      },
      columns: [
        // {data:'entity_name', orderable: false, searchable: false, visible: false},
        // {data:'project_no', orderable: false, searchable: false, visible: false},
        // {data:'debtor_acct', orderable: false, searchable: false, visible: false},
        {data:'Dates'},
        {data:'Datenames'},
        {data:'CountofVihicle', class: 'text-right'},
        {data:'AvginWH', class: 'text-right'},
        {data:'Min_in_WH', class: 'text-right'},
        {data:'Max_in_WH', class: 'text-right'},
        {data:'NotScanOut', class: 'text-right'},
      ],
    });  
}
  
$('#date_filter').daterangepicker({
  startDate: new Date(),
  locale: {
    format: 'DD/MM/YYYY',
  },
  function (start) {
    startdate = start.format('DD/MM/YYYY')
  }
  //    autoUpdateInput: false
}).change(function(){
  $('#tableList').DataTable().clear().destroy();
  var dateNow = $('#date_filter').val();
  getDataTable(dateNow)  
});

var dateNow = $('#date_filter').val();
getDataTable(dateNow);
</script>
@endpush