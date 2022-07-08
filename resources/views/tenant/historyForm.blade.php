@extends('layouts.crm_main')

    @section('content')
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Confirmation Form</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <p>Please confirm the electricity usage from the meter id {{ $data[0]->meter_id }} in {{ date("F",strtotime($data[0]->curr_read_date) ) }} / {{ date("Y",strtotime($data[0]->curr_read_date) ) }}</p>
                    <form class="form-horizontal" action="{{ route('meter.confirm') }}" method="POST">
                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0">Tenant :</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="tenant" value="{{ $data[0]->debtor_name }}" disabled="">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0">Meter ID :</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" name="meterId" id="meterId" value="{{ $data[0]->meter_id }}" readonly="">
                            <input type="hidden" class="form-control" name="row" id="row" value="{{ $data[0]->rowID }}" readonly="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0">Type ID :</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="type" value="{{ $data[0]->type }}" disabled="">
                            </div>
                        </div>

                        <br>
                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0">&nbsp;</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-xs-12 text-center">Current</div>
                                    <div class="col-sm-6 col-md-6 col-xs-12 text-center">Previous</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0"> WBP :</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-xs-12"><input type="text" class="form-control text-right" id="currentWBP" value="{{ number_format($data[0]->curr_read_high,'3','.',',') }}" disabled=""></div>
                                    <div class="col-sm-6 col-md-6 col-xs-12"><input type="text" class="form-control text-right" id="previousWBP"  value="{{ number_format($data[0]->last_read_high,'3','.',',') }}" disabled=""></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0">LWBP :</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-xs-12"><input type="text" class="form-control text-right" id="currentLWBP" value="{{ number_format($data[0]->curr_read,'3','.',',') }}" disabled=""></div>
                                    <div class="col-sm-6 col-md-6 col-xs-12"><input type="text" class="form-control text-right" id="previousLWBP" value="{{ number_format($data[0]->last_read,'3','.',',') }}" disabled=""></div>
                                </div>
                            </div>
                        </div>
                        @if($data[0]->status == 'H')
                            <br>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Confirm</button>
                                <button type="reset" class="btn iq-bg-danger">Cancel</button>
                            </div>
                        @endIf
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Image capture from meter id {{ $data[0]->meter_id }} in {{ date("F",strtotime($data[0]->curr_read_date)) }} / {{ date("Y",strtotime($data[0]->curr_read_date)) }}</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    @foreach($images as $value)
                           <img src="{{ $uri.$value }}" class="img-thumbnail">
                    @endforeach
                </div>


            </div>
        </div>

    @endsection

    
           
