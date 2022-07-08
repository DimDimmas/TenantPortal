@extends('layouts.crm_main')

@section('content')
<style>
    .form-control[disabled], fieldset[disabled] .form-control {
        cursor: not-allowed;
    }
    .new-form{
        width: 70%;
        float: left;
    }
</style>
<div class="col-sm-12 col-md-12 col-xs-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
            <h4 class="card-title"><b>Overtime Request</b></h4>
            </div>
        </div>
        <hr>
        <div class="table-responsive center">
            <div class="col-md-10">
                <form action="{{ route('overtime.store') }}" method="post" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Tenant Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ Auth::user()->tenant_person }}" disabled required>
                        <input type="hidden" name="tenantId" value="{{ Auth::user()->tenant_id }}">
                        <input type="hidden" name="tenantName" value="{{ Auth::user()->tenant_person }}">
                       
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Date Request</label>
                    <div class="col-sm-5">
                        <div class="docs-datepicker">
                            <div class="input-group">
                              <input type="text" data-toggle="datepicker" class="form-control docs-date" id="date" name="date" placeholder="Pick a date" autocomplete="off" oninvalid="this.setCustomValidity('Date not selected.')" onchange="this.setCustomValidity('')" required>
                              <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary docs-datepicker-trigger" disabled>
                                  <i class="fa fa-calendar" aria-hidden="true"></i>
                                </button>
                              </div>
                            </div>
                            <div class="docs-datepicker-container"></div>
                        </div>
                    </div>
                    <label class="col-sm-1 col-form-label">Day Request</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="day" id="day" placeholder="Day" style="background-color: #fff" disabled required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">AC</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="typeAc" id="typeAc" oninvalid="this.setCustomValidity('AC not selected.')" onchange="this.setCustomValidity('')" required>
                            <option value="">-</option>
                            @forelse ($type_overtime as $item)
                                <option value="{{ $item->type_id }}" {{ (old('typeAc') == $item->type_id) ? 'selected':'' }}>{{ $item->type_desc }}</option>
                            @empty
                                Not Found
                            @endforelse
                        </select>
                    </div>                    
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Zone</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="zone" id="zone" placeholder="Zone" oninvalid="this.setCustomValidity('Zone not filled.')" onchange="this.setCustomValidity('')" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Start Time</label>
                    <div class="col-sm-3">
                        <select class="form-control" id="start_time" name="start_time" oninvalid="this.setCustomValidity('Time start not selected.')" onchange="this.setCustomValidity('')" required>

                        </select>
                    </div>
                    <label class="col-sm-1 col-form-label">End Time</label>
                    <div class="col-sm-3">
                        <select class="form-control" id="end_time" name="end_time" oninvalid="this.setCustomValidity('Time end not selected.')" onchange="this.setCustomValidity('')" required>

                        </select>
                    </div>
                    <label class="col-sm-1 col-form-label">Duration</label>
                    <div class="col-sm-2" id="dur">
                        <input type='text' class='form-control' name='duration' id='duration' style='background-color: #fff' disabled>
                        {{-- <button id="generate" class="btn btn-success mb-5"><i class="fa fa-check"></i> generate</button> --}}
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">User</label>
                    <div class="col-sm-8 wrap_user">
                        <input type="text" class="form-control mb-3" name="user[]" id="user" oninvalid="this.setCustomValidity('User not filled.')" onchange="this.setCustomValidity('')" required>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-success add_field_button mt-1"><i class="fa fa-plus-circle" aria-hidden="true"></i>Add User</button>
                    </div>
                </div>
                <a href="{{ route('news') }}" class="btn btn-danger mb-5" style="float: right; background-color: #d9534f; border-radius: 0; border: 0"><i class="fa fa-times"></i> Close</a>
                <button type="submit" class="btn btn-success mb-5" onclick="return confirm('Send overtime request?')" style="float: right; background-color: #26B99A; border-radius: 0; border: 0"><i class="fa fa-check"></i> Send</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var msg = '{{Session::get('alert')}}';
    var exist = '{{Session::has('alert')}}';
    if(exist){
      alert(msg);
    }
</script>

@endsection

@push('scripts')
{{ HTML::script('js/overtime.js') }}
@endpush