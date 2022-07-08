@extends('layouts.crm_main')

    @section('content')

    <div class="col-sm-12 col-md-12 col-xs-12">
        <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                <h4 class="card-title"><b>Meter Reading {{ date("F - Y", strtotime(now())) }}</b></h4>
                </div>
            </div>
            <div class="table-responsive center">
                <table id="datareading" class="easyui-datagrid" style="width:100%; max-height:500px;">
                    <thead>
                        <tr>
                            <th field="entity_name">Entity</th>
                            <th field="descs">Project</th>
                            <th field="panel">Panel No</th>
                            <th field="type">Type</th>
                            <th field="meter_id">Meter ID</th>
                            <th field="curr_read_high">WBP</th>
                            <th field="curr_read">LWBP</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title">Image capture from meter id  in {{ date("F",strtotime($data[0]->curr_read_date)) }} / {{ date("Y",strtotime($data[0]->curr_read_date)) }}</h4>
                </div>
            </div>
            <div class="iq-card-body">
                @foreach($images as $value)
                       <img src="ass="img-thumbnail">
                @endforeach
            </div>


        </div>
    </div>
        </div>
    </div> --}}
   
    @endsection

    @push('scripts')
    {{ HTML::script('js/list_meter_confirm.js') }}
    @endpush