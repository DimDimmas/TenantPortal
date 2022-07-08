@extends('layouts.crm_main')

    @section('content')
    <div class="col-sm-12">
        <div class="iq-card">
              <div class="iq-card-header d-flex justify-content-between">
                <div class="col-sm-12 col-lg-6">
                    <div class="iq-header-title">
                        <h4 class="card-title"><b>History Meter Reading</span></b></h4>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-6">
                    <div class="input-group input-daterange align-right" style="float: right; width: auto !important;">
                        <input id="dateFrom" type="text" class="easyui-datebox" style="text-align:center">
                        <div class="input-group-addon">to</div>
                        <input id="dateTo" type="text" class="easyui-datebox" style="text-align:center">
                    </div>
                 </div>
              </div>
              <div class="iq-card-body">
                 <div class="table-responsive">
                    <table id="datahistory" class="easyui-datagrid" style="width:100%; max-height:500px;">
                        <thead>
                            <tr>
                                <th field="action" rowspan="2">Action</th>
                                <th field="entity_name" rowspan="2">Entity</th>
                                <th field="project_name" rowspan="2">Project</th>
                                <th field="refNo" rowspan="2">Panel No</th>
                                <th field="meter_id" rowspan="2">Meter ID</th>
                                <th field="type" rowspan="2">Type</th>
                                <th colspan="2">Last Read</th>
                                <th colspan="2">Current Read</th>
                                <th rowspan="2">Usage</th>
                                <th rowspan="2">Usage High</th>
                                <th rowspan="2">Amount</th>
                            </tr>
                            <tr>
                                <th>LWBP</th>
                                <th>WBP</th>
                                <th>LWBP</th>
                                <th>WBP</th>
                            </tr>
                        </thead>
                        <tbody>
      
                        </tbody>
                        
                    </table>
                 </div>

                 <div class="modal fade " id="view" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewTitle">Detail Tenant Information</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="dlg" title="Detail" style="width:100%;">
                                    <div class="row">
                                        <div class="col-sm-7">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Tenant:</label>
                                                    <div class="col-sm-9">
                                                    <input type="text" readonly class="form-control" id="tenant" value="">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Panel:</label>
                                                    <div class="col-sm-9">
                                                    <input type="text" readonly class="form-control" id="panel" value="">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Meter ID:</label>
                                                    <div class="col-sm-9">
                                                    <input type="text" readonly class="form-control" id="meter_id" value="">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Type:</label>
                                                    <div class="col-sm-9">
                                                    <input type="text" readonly class="form-control" id="type" value="">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Reading:</label>
                                                    <div class="col-sm-9">
                                                    <input type="text" readonly class="form-control" id="read_date" value="">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label"></label>
                                                    <div class="col-sm-9">
                                                        <div class="row">
                                                            <div class="col-sm-6 col-md-6 col-xs-12 text-center">Previous</div>
                                                            <div class="col-sm-6 col-md-6 col-xs-12 text-center">Current</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">LWBP</label>
                                                    <div class="col-sm-9">
                                                        <div class="row">
                                                            <div class="col-sm-6 col-md-6 col-xs-12 text-center"><input type="text" readonly class="form-control text-right" id="last_lwbp" value=""></div>
                                                            <div class="col-sm-6 col-md-6 col-xs-12 text-center"><input type="text" readonly class="form-control text-right" id="curr_lwbp" value=""></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">WBP</label>
                                                    <div class="col-sm-9">
                                                        <div class="row">
                                                            <div class="col-sm-6 col-md-6 col-xs-12 text-center"><input type="text" readonly class="form-control text-right" id="last_wbp" value=""></div>
                                                            <div class="col-sm-6 col-md-6 col-xs-12 text-center"><input type="text" readonly class="form-control text-right" id="curr_wbp" value=""></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                        </div>
                                        <div class="col-sm-5" id="image">
                                        </div>
                                    </div>
                                </div>
                                <div class="signature" style="margin-top:20px">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="check text-center">Checked By:</div>
                                            <div id="signature_tenant" class="text-center" style="width:100%; height:150px"></div>
                                            <div class="tenant_name text-center" id="tenant_name"></div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="check text-center">Capture By:</div>
                                            <div id="signature_tenant" class="text-center" style="width:300px; height:150px"></div>
                                            <div class="tenant_name text-center" id="engineering_name"></div>
                                        </div>
                                     </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade " id="show_images" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewTitle">Image Capture</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <div class="modal-body" id="image_capture">
                            
                        </div>
                    
                    </div>
            
            
                    </div>
                </div>

              </div>
           </div>
    </div>




    @endsection

    @push('scripts')
    {{ HTML::script('js/list_meter_history.js') }}
    @endpush