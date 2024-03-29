@extends('layouts.crm_main')

@section('content')
<style>
  .btn{
    /* border-radius: 0px; */
    text-align: center;
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
      <h4 class="card-title"><b>Form Lost Ticket - <i>{{ $tenant->company_name }}</i></b></h4>
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
        </div>
        <table id="tableList" class="display mb-2" style="color: #353535">
          <thead>
            <tr>
              <th>Action</th>
              <th>Capture</th>
              <th>KTP</th>
              <th>Scan In</th>
              <th>Scan Out</th>
              <th width="150px">Duration</th>
              <th>Type</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
@include('tracking_loading.notscanout.scripts.scriptIndex')
@endpush