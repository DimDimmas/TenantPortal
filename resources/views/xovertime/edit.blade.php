@extends('layouts.crm_main')

@section('content')
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
        width: 70%;
        float: left;
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
            <div class="col-md-10">
                <form action="/overtime/history-ticket/modify/modified/{{ $list->overtime_code }}" method="post" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Ticket No</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="ticketCode" id="ticketCode" value="{{ $list->overtime_code }}" disabled required>
                    </div>
                </div>
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
                </div>
                <a href="{{ route('history_overtime') }}" class="btn btn-danger mb-5" style="float: right; background-color: #d9534f; border-radius: 0; border: 0"><i class="fa fa-times"></i> Close</a>
                <button type="submit" class="btn btn-success mb-5" onclick="return confirm('Update this overtime data?')" style="float: right; background-color: #26B99A; border-radius: 0; border: 0"><i class="fa fa-check"></i> Send</button>
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
@endif
@endsection

@push('scripts')
{{ HTML::script('js/overtime.js') }}
@endpush