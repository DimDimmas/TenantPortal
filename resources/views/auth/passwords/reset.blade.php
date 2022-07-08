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
            <h4 class="card-title"><b>Reset Password</b></h4>
            </div>
        </div>
        <hr>

        <div class="iq-card-body pl-5">
            <form method="POST" action="/submit_new_password">
                {{ csrf_field() }}
            <div class="form-group row">
                <div class="col-md-12">
                   <div class="form-group col-sm-4 float-left">
                        <label for="password">New Password:</label>
                        <input type="password" class="form-control @if($errors->has('password'))('password') is-invalid @endif" name="password" id="password" required>
                        @if($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-sm-4 float-left">
                        <label for="password_confirmation">Confirm Password:</label>
                        <input type="password" class="form-control @if($errors->has('password_confirmation'))('password_confirmation') is-invalid @endif" name="password_confirmation" id="password_confirmation" required>
                        @if($errors->has('password_confirmation'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <a href="{{ route('news') }}" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection