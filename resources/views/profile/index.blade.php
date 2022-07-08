@extends('layouts.crm_main')

@section('content')
<style>
    .form-control[readonly]{
        background-color: #fff;
        border: none;
        border-bottom: 1px solid #b2bec3
    }
</style>

@if ($tenant->tenant_code != Auth::user()->tenant_code)
    <script>
        window.location = "{{ route('news') }}";
    </script>
@else

<div class="col-sm-12 col-md-12 col-xs-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
            <h4 class="card-title"><b>Profile</b></h4>
            </div>
        </div>
        <hr>

        <div class="iq-card-body pl-5">
            <div class="form-group row">
                <div class="col-md-12">
                   <div class="profile-img-edit float-left mr-5">
                        <img class="profile-pic" src="/img/photo-profile/user.png" alt="profile-pic">
                        {{-- <div class="p-image">
                            <i class="ri-pencil-line upload-button"></i>
                            <input class="file-upload" type="file" accept="image/*"/>
                        </div> --}}
                   </div>
                   <div class="form-group col-sm-4 float-left">
                        <label for="fname">Contact Person:</label>
                        <input type="text" class="form-control" id="fname" value="{{ $tenant->tenant_person }}" readonly>
                    </div>
                    <div class="form-group col-sm-4 float-left">
                        <label for="lname">Tenant Name:</label>
                        <input type="text" class="form-control" id="lname" value="{{ $tenant->company_name }}" readonly>
                    </div>
                    <div class="form-group col-sm-4 float-left">
                        <label for="uname">Token:</label>
                        <input type="text" class="form-control" id="uname" value="{{ $tenant->tenant_token }}" readonly>
                    </div>
                </div>
                <div class="col-md-10">
                    <a href="{{ route('news') }}" class="btn btn-danger" style="float: right; background-color: #d9534f; border-radius: 0; border: 0"><i class="fa fa-times"></i> Close</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection