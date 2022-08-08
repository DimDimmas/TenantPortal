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
          <table id="tableList" class="mb-2 table table-sm" style="color: #353535">
            <thead>
                <tr>
                  <th>Entity Project</th>
                  <th>Project No</th>
                  <th>Debtor Acct</th>
                  <th>Date</th>
                  <th class="text-right">In</th>
                  <th class="text-right">Out</th>
                  <th class="text-right">Total</th>
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
<script>
function getDataTable(dateSelected){
    $('#tableList').DataTable({
      order: [[0, 'desc']],
      processing: true,
      serverSide: true,
      ordering: false,
      ajax: {
        url: '/tracking-loading/report-summary/get-data-table',
        method: 'GET',
        data: {dateSelected:dateSelected}
      },
      columns: [
        {data:'entity_project', orderable: false, searchable: false, visible: false},
        {data:'project_no', orderable: false, searchable: false, visible: false},
        {data:'debtor_acct', orderable: false, searchable: false, visible: false},
        {data:'date', render: function(data, type, row, meta){
          return moment(data).format('DD/MM/YYYY');
        }},
        {data:'total_not_scan_out', class: 'text-right'},
        {data:'total_has_scan_out', class: 'text-right'},
        {data:'total', class: 'text-right'},
        // {data:'Max_in_WH', sClass: 'alignRight'},
        // {data:'total', sClass: 'alignRight'},
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