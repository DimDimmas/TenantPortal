@extends('layouts.crm_main')

    @section('content')
    <div class="col-sm-12">
        <div class="iq-card-header">
            <div class="related-heading text-center">
                <h3 class="card-title">News</h3>
            </div>
        </div>
        
        <div class="iq-card-body">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div id="tenant_news"></div>
                </div>

            </div>
        </div>
    </div>
   
    @endsection

    @push('scripts')
    {{ HTML::script('js/list_news.js') }}
    @endpush