@extends('layouts.crm_main')

@section('content')
@if(empty(Auth::user()))
   <script>
      window.location = "{{ route('logout') }}";
   </script>
@elseif(Auth::user()->entity_project == '301502' && trim(Auth::user()->project_no) == '210101')
<style>
    .form-control[disabled], fieldset[disabled] .form-control {
        cursor: not-allowed;
    }
    .new-form{
        width: 85%;
        float: left;
    }
    .select2.select2-container .select2-selection{
        height: 45px;
        line-height: 45px;
        padding: .375rem .35rem;
        font-size: 14px;
        color: #d7dbda;
        border: 1px solid #d7dbda;
        border-radius: 10px;
    }
    .select2-selection__arrow{
        margin-top: 8px;
    }
    .select2.select2-container .select2-selection {
        line-height: 20px;
        font-size: 14px;
        color: #333;
    }
    .fl-left{
        float: left;
    }
    #remove_zone{
        width: 100%;
    }
    select option{
        padding: 5px;
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

        @if ($message = Session::get('success'))
        <div class="toast text-white fade show" role="alert" aria-live="assertive" aria-atomic="true" data-animation="true" data-delay="5000" data-autohide="true" style="position: fixed; top: 0; right: 0; z-index: 99; background-color: #27b3458a; margin: 15px; transition: 0.5s">
            <div class="toast-header text-white" style="background-color: #27b3458a;">
                <i class="fa fa-bell" aria-hidden="true"></i>
                &nbsp;
                <strong class="mr-auto">Notification</strong>
                <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                {{ $message }}
            </div>
        </div>
        @elseif ($message = Session::get('error'))
        <div class="toast text-white fade show" role="alert" aria-live="assertive" aria-atomic="true" data-animation="true" data-delay="5000" data-autohide="true" style="position: fixed; top: 0; right: 0; z-index: 99; background-color: #b63d3d; margin: 15px; transition: 0.5s">
            <div class="toast-header text-white" style="background-color: #b63d3d;">
                <i class="fa fa-bell" aria-hidden="true"></i>
                &nbsp;
                <strong class="mr-auto">Notification</strong>
                <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                {{ $message }}
            </div>
        </div>
        
        @endif

        <div class="table-responsive center">
            <form action="{{ route('overtime.store') }}" id="form_overtime" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="col-md-6" style="float: left">
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Contact Person</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ Auth::user()->tenant_person }}" disabled required>
                        <input type="hidden" name="tenantId" value="{{ Auth::user()->tenant_id }}">
                        <input type="hidden" name="tenantName" value="{{ Auth::user()->tenant_person }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Date Request</label>
                    <div class="col-sm-4">
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
                    <label class="col-sm-1 col-form-label">Day</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="day" id="day" placeholder="Day" style="background-color: #fff" readonly required>
                    </div>
                </div>
                <div class="mb-3 row wrap_zone">
                    <label class="col-sm-2 col-form-label">Zone</label>
                    <div class="col-sm-4">
                        <select name="zone[]" id="zone" class="form-control zone" oninvalid="this.setCustomValidity('Zone not selected.')" onchange="this.setCustomValidity('')" required>
                            <option value="">Choose..</option>
                            @foreach ($get_ovt_zone as $zone)
                                <option value="{{ $zone->zone_descs }}">{{ $zone->zone_descs }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="col-sm-1 col-form-label">AC</label>
                    <div class="col-sm-3">
                        <select class="form-control" name="typeAc[]" id="typeAc" oninvalid="this.setCustomValidity('AC not selected.')" onchange="this.setCustomValidity('')" required>
                            <option value="">Choose..</option>
                            @forelse ($type_overtime as $item)
                                <option value="{{ $item->type_id }}" {{ (old('typeAc') == $item->type_id) ? 'selected':'' }}>{{ $item->type_desc }}</option>
                            @empty
                                Not Found
                            @endforelse
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-success add_row_zoneac mt-1" style="border-radius: 0px"><i class="fa fa-plus-circle" aria-hidden="true" style="margin-right: 5px"></i> Add</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6" style="float: left">
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Start Time</label>
                    <div class="col-sm-4">
                        <select class="form-control" id="start_time" name="start_time" oninvalid="this.setCustomValidity('Time start not selected.')" onchange="this.setCustomValidity('')" required>

                        </select>
                    </div>
                    <label class="col-sm-2 col-form-label">End Time</label>
                    <div class="col-sm-4">
                        <select class="form-control" id="end_time" name="end_time" oninvalid="this.setCustomValidity('Time end not selected.')" onchange="this.setCustomValidity('')" required>

                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Duration</label>
                    <div class="col-sm-4" id="dur">
                        <input type='text' class='form-control' name='duration' id='duration' style='background-color: #fff' disabled>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">User</label>
                    <div class="col-sm-8 wrap_user">
                        <input type="text" class="form-control" name="user[]" id="user" oninvalid="this.setCustomValidity('User not filled.')" onchange="this.setCustomValidity('')" required>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-success add_field_button mt-1" style="border-radius: 0px"><i class="fa fa-plus-circle" aria-hidden="true"></i>Add</button>
                    </div>
                </div>
                <button type="button" id="btnSubmit" class="btn btn-success mb-5" style="float: right; background-color: #26B99A; border-radius: 0; border: 0"><i class="fa fa-check"></i> Send</button>
                <a href="{{ route('news') }}" class="btn btn-danger mb-5" style="float: right; background-color: #d9534f; border-radius: 0; border: 0"><i class="fa fa-times"></i> Close</a>
            </div>
            </form>
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
@else
    <script>
        window.location = "{{ route('news') }}";
    </script>
@endif
@endsection

@push('scripts')
{{ HTML::script('js/overtime.js') }}
@endpush