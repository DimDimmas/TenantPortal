@extends('layouts.crm_main')

@section('content')
<style>
  .btn{
    border-radius: 0px;
  }

  .select2.select2-container .select2-selection{
    height: 45px;
    line-height: 45px;
    padding: .375rem .10rem;
    font-size: 14px;
    color: #d7dbda;
    border: 1px solid #d7dbda;
    border-radius: 10px;
  }
  .select2-selection__arrow{
    margin-top: 8px;
  }

  .text-right{
    text-align: right;
  }

  .text-center{
    text-align: center;
  }

  .row{
    margin-bottom: 5px;
  }

  input, select, textarea{
    background-color: #fff !important;
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
      <h4 class="card-title"><b>Invoice History - <i>{{ $tenant->company_name }}</i></b></h4>
      </div>
    </div>
    <hr>
    <div class="table-responsive center mt-5">
      <div class="col-xs-auto col-sm-auto col-md-auto col-lg-auto mb-3">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-5 pull-right">          
          <div class="pull-right">
            <select class="form-control select2" name="filter_month" id="filter_month">
              @foreach ($filter_month as $item)
                  <option value="{{ $item['id'] }}" {{ $item['id'] == Carbon::now()->format('m') ? 'selected' : '' }}>{{ $item['text'] }}</option>
              @endforeach
            </select>
            <select class="form-control select2" name="filter_year" id="filter_year">
              @foreach ($filter_year as $item)
                  <option value="{{ $item->year }}" {{ $item->year == Carbon::now()->format('Y') ? 'selected' : '' }}>{{ $item->year }}</option>
              @endforeach
            </select>
            {{-- <button class="btn btn-danger" id="btnGeneratePdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> &nbsp; Generate PDF</button>
            <button class="btn btn-success" id="btnGenerateExcel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> &nbsp; Generate Excel</button> --}}
          </div>
        </div>
        <table id="tableList" class="display mb-2" style="color: #353535">
          <thead>
            <tr>
              <th>No</th>
              <th>Invoice No</th>
              <th>Description</th>
              <th>Invoice Amount</th>
              <th>Delivery Date</th>
              <th>Receipt Date</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th colspan="3"></th>
              <th style="text-align: right;"></th>
              <th></th>
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>



<!-- Modal -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
        <div class="col-12">
          <div class="row">
            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
              
              <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                  <div class="row">
                    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Invoice No</label>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <input type="text" class="form-control" id="invoice_no" readonly>
                    </div>
                  </div>
                  <div class="row">
                    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Description</label>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <textarea class="form-control" id="description" cols="120" rows="2" readonly></textarea>
                    </div>
                  </div>
                  <div class="row">
                    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Invoice Amount</label>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <input type="text" class="form-control" id="invoice_amount" readonly>
                    </div>
                  </div>
                  <div class="row">
                    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Balance</label>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <input type="text" class="form-control" id="balance" readonly>
                    </div>
                  </div>
                </div>
  
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                  <div class="row">
                    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Delivery No</label>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <input type="text" class="form-control" id="delivery_no" readonly>
                    </div>
                  </div>
                  <div class="row">
                    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Delivery Date</label>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <div class="input-group">
                        <input type="text" class="form-control" id="delivery_date" readonly>
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fas fa-calendar    "></i></span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Delivery Name</label>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <input type="text" class="form-control" id="delivery_name" readonly>
                    </div>
                  </div>
                  <div class="row">
                    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Receiver Date</label>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <input type="text" class="form-control" id="receiver_date" readonly>
                    </div>
                  </div>
                  <div class="row">
                    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Receiver Name</label>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <input type="text" class="form-control" id="receiver_name" readonly>
                    </div>
                  </div>           
                  <div class="row">
                    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Due Date</label>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <div class="input-group">
                        <input type="text" class="form-control" id="due_date" readonly>
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fas fa-calendar    "></i></span>
                        </div>
                      </div>
                    </div>
                  </div>
                  {{-- <div class="row">
                    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Receipt No</label>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <input type="text" class="form-control" id="receipt_no" readonly>
                    </div>
                  </div> --}}
                  {{-- <div class="row">
                    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Receipt Date</label>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <div class="input-group">
                        <input type="text" class="form-control" id="receipt_date" readonly>
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fas fa-calendar    "></i></span>
                        </div>
                      </div>
                    </div>
                  </div> --}}
                  {{-- <div class="row">
                    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Receipt Amount</label>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <input type="text" class="form-control" id="receipt_amount" readonly>
                    </div>
                  </div> --}}                  
                </div>              
              </div>
              
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
              <div id="container-attachment">
                <iframe id="frame-attachment" width="100%" height="500px" frameborder="0"></iframe>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> &nbsp; Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
@include('invoice.history.scripts.scriptIndex')
@endpush