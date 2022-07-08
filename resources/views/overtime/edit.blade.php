@extends('layouts.crm_main')

@section('content')
@if(empty(Auth::user()))
   <script>
      window.location = "{{ route('logout') }}";
   </script>
@elseif(Auth::user()->entity_project == '301502' && trim(Auth::user()->project_no) == '210101')
    @if ($list->tenant_id != Auth::user()->tenant_id)
        <script>
            window.location = "{{ route('history_overtime') }}";
        </script>
    @else
    <style>
        .form-control[disabled], fieldset[disabled] .form-control {
            cursor: not-allowed;
        }
        .new-form{
            width: 85%;
            float: left;
        }
        .fl-left{
        float: left;
        }
        #remove_zone{
            width: 100%;
        }
        .col-md-6{
            margin-bottom: 30px;
        }
    </style>
    <div class="col-sm-12 col-md-12 col-xs-12">
        <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                <h4 class="card-title"><b>Modify Overtime Data</b></h4>
                </div>
            </div>
            <hr>
            <div class="table-responsive center">
                <div class="col-md-12">
                    <form action="/overtime/history-ticket/modify/modified/{{ $list->overtime_code }}" method="post" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="col-md-6" style="float: left">
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Ticket No</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="ticketCode" id="ticketCode" value="{{ $list->overtime_code }}" disabled required>
                            </div>
                        </div>
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
                                      <input type="text" class="form-control" id="date" name="date" placeholder="Pick a date" autocomplete="off" value="{{ \Carbon\Carbon::parse($list->overtime_date)->format('d/m/Y') }}" readonly required>
                                      <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary docs-datepicker-trigger" disabled>
                                          <i class="fa fa-calendar" aria-hidden="true"></i>
                                        </button>
                                      </div>
                                    </div>
                                </div>
                            </div>
                            <label class="col-sm-1 col-form-label">Day</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="day" id="day" value="{{ \Carbon\Carbon::parse($list->overtime_date)->format('l') }}" readonly required>
                            </div>
                        </div>
                        <div class="mb-3 row wrap_zone">
                            <label class="col-sm-2 col-form-label">Zone</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" 
                                value="@foreach ($get_ovt_details as $zone) {{ $zone->overtime_zone }}, @endforeach" 
                                readonly>
                                <small>Zone Before</small>
                            </div>
                            <label class="col-sm-1 col-form-label">AC</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" 
                                value="@foreach ($get_ovt_details as $type) {{ $type->type_desc }}, @endforeach" 
                                readonly>
                                <small>AC Before</small>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-success add_row_zoneac mt-1" style="border-radius: 0px"><i class="fa fa-plus-circle" aria-hidden="true"></i>Add</button>
                            </div>
                            @foreach ($get_ovt_details as $ovt_detail)
                                <div id="remove_zone">
                                    <label style="margin-top:10px" class="col-sm-2 col-form-label fl-left"></label>
                                    <div style="margin-top:10px" class="col-sm-4 fl-left">
                                        <select name="zone[]" id="zone" class="form-control zone">
                                            <option value="">Choose..</option>
                                            @foreach ($get_ovt_zone as $zone)
                                                <option value="{{ $zone->zone_descs }}" {{ ($ovt_detail->overtime_zone == $zone->zone_descs) ? 'selected':'' }}>{{ $zone->zone_descs }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label style="margin-top:10px" class="col-sm-1 col-form-label fl-left"></label>
                                    <div style="margin-top:10px" class="col-sm-3 fl-left">
                                        <select class="form-control" name="typeAc[]" id="typeAc">
                                        @foreach ($type_overtime as $item)
                                            <option value="{{ $item->type_id }}" @if($ovt_detail->overtime_type === $item->type_id) selected @endif>{{ $item->type_desc }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <a href="#" class="col-sm-2 remove_field_zone fl-left" style="font-size: 25px; margin-top: 10px;"><i class="fa fa-times-circle" aria-hidden="true" style="color: #d9534f"></i></a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-6" style="float: left">
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Start Time</label>
                            <div class="col-sm-4">
                                <select class="form-control" id="start_time" name="start_time">
                                    @foreach ($get_start_time as $item)
                                        <option value="{{ $item->times }}" @if($item->times.':00' === $list->overtime_start) selected @endif>{{ $item->times }}</option>
                                    @endforeach
                                </select>
                                <small>Start Time Before Modify: {{ $list->overtime_start }}</small>
                            </div>
                            <label class="col-sm-2 col-form-label">End Time</label>
                            <div class="col-sm-4">
                                <select class="form-control" id="end_time" name="end_time">
                                    @foreach ($get_start_time as $item)
                                        <option value="{{ $item->times }}" @if($item->times.':00' === $list->overtime_end) selected @endif>{{ $item->times }}</option>
                                    @endforeach
                                </select>
                                <small>End Time Before Modify: {{ $list->overtime_end }}</small>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Duration</label>
                            <div class="col-sm-4" id="dur">
                                <input type="text" class="form-control" name="duration" id="duration" value="{{ $list->overtime_duration }}" style='background-color: #fff' disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">User</label>
                            <div class="col-sm-8 wrap_user">
                                <input type="text" 
                                    value="@foreach ($user as $user) {{ $user->overtime_user }}, @endforeach"
                                    class="form-control" disabled>
                                <small>User Before</small>
                                @foreach ($user_list as $user_list)
                                    <div class="col-sm-12 mb-3" style="padding-left: 0; margin-top: 10px">
                                        <input type="text" class="form-control new-form mr-3" name="user[]" id="user" value="{{ $user_list->overtime_user }}" required>
                                        <a href="#" class="remove_field_user" style="font-size: 25px;"><i class="fa fa-times-circle" aria-hidden="true" style="color: #d9534f"></i></a>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-success add_field_button mt-1" style="border-radius: 0px"><i class="fa fa-plus-circle" aria-hidden="true"></i>Add</button>
                            </div>
                        </div>
                    <button type="submit" class="btn btn-success mb-5" onclick="return confirm('Update this overtime data?')" style="float: right; background-color: #26B99A; border-radius: 0; border: 0"><i class="fa fa-check"></i> Send</button>
                    <a href="{{ route('history_overtime') }}" class="btn btn-danger mb-5" style="float: right; background-color: #d9534f; border-radius: 0; border: 0"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back to History</a>
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
    @endif
@else
<script>
    window.location = "{{ route('news') }}";
</script>
@endif
@endsection

@push('scripts')
{{ HTML::script('js/overtime.js') }}
@endpush









{{-- <div class="mb-3 row">
    <label class="col-sm-2 col-form-label">Ticket No</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="ticketCode" id="ticketCode" value="{{ $list->overtime_code }}" disabled required>
    </div>
</div>
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
    <div class="col-sm-5">
        <input type="text" name="date" id="date" value="{{ \Carbon\Carbon::parse($list->overtime_date)->format('Y/m/d') }}" class="form-control" readonly>
    </div>
    <label class="col-sm-1 col-form-label">Day Request</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" name="day" id="day" placeholder="Day" value="{{ \Carbon\Carbon::parse($list->overtime_date)->format('l') }}" readonly>
    </div>
</div>
<div class="mb-3 row">
    <label class="col-sm-2 col-form-label">AC</label>
    <div class="col-sm-10">
        <select class="form-control" name="typeAc" id="typeAc" required>
            <option value="">-</option>
            @forelse ($type_overtime as $item)
                <option value="{{ $item->type_id }}" @if($item->type_id === $list->overtime_type) selected @endif>{{ $item->type_desc }}</option>
            @empty
                Not Found
            @endforelse
        </select>
        <small>AC Before Modify: {{ $list->type_desc }}</small>
    </div>
</div>
<div class="mb-3 row">
    <label class="col-sm-2 col-form-label">Zone</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="zone" id="zone" placeholder="Zone" value="{{ $list->overtime_zone }}" required>
        <small>Zone Before Modify: {{ $list->overtime_zone }}</small>
    </div>
</div>
<div class="mb-3 row">
    <label class="col-sm-2 col-form-label">Start Time</label>
    <div class="col-sm-3">
        <select class="form-control" id="start_time" name="start_time" required>
            @foreach ($get_start_time as $item)
                <option value="{{ $item->times }}" @if($item->times.':00' === $list->overtime_start) selected @endif>{{ $item->times }}</option>
            @endforeach
        </select>
        <small>Time Start Before Modify: {{ $list->overtime_start }}</small>
    </div>
    <label class="col-sm-1 col-form-label">End Time</label>
    <div class="col-sm-3">
        <select class="form-control" id="end_time" name="end_time" oninvalid="this.setCustomValidity('Waktu selesai belum dipilih. Klik Start Time dahulu untuk menampilkan pilihan.')" onchange="this.setCustomValidity('')" required>
            @foreach ($get_start_time as $item)
                <option value="{{ $item->times }}" @if($item->times.':00' === $list->overtime_end) selected @endif>{{ $item->times }}</option>
            @endforeach
        </select>
        <small>Time End Before Modify: {{ $list->overtime_end }}</small>
    </div>
    <label class="col-sm-1 col-form-label">Duration</label>
    <div class="col-sm-2" id="dur">
        <input type='text' class='form-control' name="duration" id='duration' value="{{ $list->overtime_duration }}" style='background-color: #fff' disabled>
    </div>
</div>
<div class="mb-3 row">
    <label class="col-sm-2 col-form-label">User (Before)</label>
    <div class="col-sm-10">
        <input type="text" 
        value="@foreach ($user as $user) {{ $user->overtime_user }}, @endforeach"
        class="form-control" readonly>
    </div>                    
</div>
<div class="mb-3 row">
    <label class="col-sm-2 col-form-label">User (After)</label>
    <div class="col-sm-8 wrap_user">
        <input type="text" class="form-control mb-3" name="user[]" id="user">
    </div>
    <div class="col-sm-2">
        <button class="btn btn-success add_field_button mt-1"><i class="fa fa-plus-circle" aria-hidden="true"></i>Add User</button>
    </div>
</div> --}}
