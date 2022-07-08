@extends('layouts.crm_main')

@section('content')
    <style>
        .ui-datepicker-calendar {
            display: none;
        }

    </style>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        {{-- <div class="iq-card"> --}}
            <div class="iq-card-header d-flex justify-content-between">
                <div class="col-sm-12 col-lg-6">
                    <div class="iq-header-title">
                        <h4 class="card-title">Summary Report - Utillities</h4>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-6">
                    {{-- <div class="input-group input-daterange align-right" style="float: right; width: auto !important;"> --}}
                        <div class="row pull-right">
                            <div class="col-sm-12 col-lg-6">
                                <input type="input" id="InputMonth" value="{{ date('Y') }}" name="year"
                                style="background-color:#FFF; text-align:center"
                                class="right SetReport form-control d-flex align-items-center">
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                <select class="form-control" id="type" name="type" style="background-color:#FFF;">
                                    <option value="E">Electricity</option>
                                    <option value="W">Water</option>
                                </select>
                            </div>
                        </div>
                        
                        
                    {{-- </div> --}}
                 </div>
            </div>
        {{-- </div> --}}
    </div>
            
            
    <div class="col-12">
        <div id="chartContainer" style="height: 370px; width: 100%;"></div>
    </div>
@endsection

@push('scripts')
    {{ HTML::script('/js/summary.js') }}
@endpush
