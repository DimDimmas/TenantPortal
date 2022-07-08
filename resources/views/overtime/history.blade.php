@extends('layouts.crm_main')

@section('content')
@if(empty(Auth::user()))
   <script>
      window.location = "{{ route('logout') }}";
   </script>
@elseif(Auth::user()->entity_project == '301502' && trim(Auth::user()->project_no) == '210101')
<style>
    .alert .close{
        color: unset;
    }
    .btn i{
        margin-right: 0;
        color: #fff;
    }
    .dataTables_filter{
        display: none;
    }
    .dataTables_wrapper .dataTables_length {
        float: right;
    }
    tfoot th button, input {
        /* width: 100%; */
        padding: 3px;
        box-sizing: border-box;
        font-weight: normal;
    }
    .dt-search ::placeholder{
        color: #2f3640;
        font-weight: bold;
    }
</style>
<div class="col-sm-12 col-md-12 col-xs-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
            <h4 class="card-title"><b>Overtime Request History</b></h4>
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
        <div class="toast text-white fade show" role="alert" aria-live="assertive" aria-atomic="true" data-animation="true" data-delay="5000" data-autohide="true" style="position: fixed; top: 0; right: 0; z-index: 99; background-color: #27b3458a; margin: 15px; transition: 0.5s">
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

        <div class="table-responsive center mt-5">
            <div class="col-md-auto mb-3">
                <table class="mb-3" border="0">
                    <tbody>
                        <tr>
                            <td>Show Status By</td>
                            <td>
                            <select class="form-control ml-2" name="statusOvertimeBy" id="statusOvertimeBy">
                                <option value="">All</option>
                                <option value="New">New</option>
                                <option value="Request Modify">Request Modify</option>
                                <option value="Approve Modify">Approve Modify</option>
                                <option value="Approve">Approve</option>
                                <option value="Close">Close</option>
                                <option value="Done">Done</option>
                            </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table id="tableOvertime" class="display mb-2" style="color: #353535">
                    <thead>
                        <tr style="font-weight: bold">
                            <td><center>Action</center></td>
                            <td hidden>no</td>
                            <th class="dt-search">Overtime No</th>
                            <th class="dt-search">Contact Person</th>
                            <th class="dt-search">AC</th>
                            <th class="dt-search">Zone</th>
                            <th class="dt-search">Date Request</th>
                            <th class="dt-search">Time Start</th>
                            <th class="dt-search">Time End</th>
                            <th class="dt-search">Duration</th>
                            <th class="dt-search">PIC Technician</th>
                            <th class="dt-search">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($overtime_list as $item)
                            @if ($item->tenant_id == Auth::user()->tenant_id)
                                <tr>
                                    <td>
                                        <center>
                                            @if ($item->tenant_id == Auth::user()->tenant_id)
                                                <a style="text-decoration: underline; cursor: pointer;"
                                                    id="btnShowOvertime"
                                                    class="btn btn-primary mt-1 btn-show"
                                                    data-toggle="modal" data-target="#showOvertimeModal{{ $item->overtime_code }}"
                                                    data-code="{{ $item->overtime_code }}" 
                                                    data-tenant="{{ $item->tenant_person }}" 
                                                    {{-- data-ac="{{ $item->type_desc }}"  --}}
                                                    data-zone="{{ $item->overtime_zone }}" 
                                                    data-date="{{ \Carbon\Carbon::parse($item->overtime_date)->format('Y/m/d') }}" 
                                                    data-start="{{ \Carbon\Carbon::parse($item->overtime_start)->format('H:i:s') }}" 
                                                    data-end="{{ \Carbon\Carbon::parse($item->overtime_end)->format('H:i:s') }}" 
                                                    data-duration="{{ \Carbon\Carbon::parse($item->overtime_duration)->format('H:i:s') }}" 
                                                    data-user="{{ $item->overtime_user }}" 
                                                    data-status="{{ $item->status_name }}" 
                                                    data-day="{{ \Carbon\Carbon::parse($item->overtime_date)->format('l') }}" >
                                                <i class="fa fa-search" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="View"></i></a>
                                                &nbsp;
                                            @else
                                                -
                                            @endif
                                        </center>
                                    </td>
                                    <td hidden>1</td>
                                    <td>{{ $item->overtime_code }}</td>
                                    <td>{{ $item->tenant_person }}</td>
                                    <td>{{ $item->overtime_type }}</td>
                                    <td>{{ $item->overtime_zone }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->overtime_date)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->overtime_start)->format('H:i:s') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->overtime_end)->format('H:i:s') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->overtime_duration)->format('H:i:s') }}</td>
                                    <td>{{ $item->overtime_user }}</td>
                                    <td>
                                        <center>
                                        @if ($item->overtime_status == "0" || $item->overtime_status == "")
                                            Pending
                                        @else
                                            {{ $item->status_name }}
                                        @endif
                                        </center>
                                    </td>
                                </tr>

                                <!-- Modal Show -->
                                <div class="modal fade bd-example-modal-lg" id="showOvertimeModal{{ $item->overtime_code }}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Overtime Request Detail</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3 row">
                                                    <label class="col-sm-2 col-form-label">Overtime Code</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="status" id="status" class="form-control" value="{{ $item->overtime_code }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-sm-2 col-form-label">Contact Person</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="tenantName" id="tenantName" class="form-control" value="{{ $item->tenant_person }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-sm-2 col-form-label">Status</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="status" id="status" class="form-control" value="{{ $item->status_name }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-sm-2 col-form-label">Date Request</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" name="date" id="date" class="form-control" value="{{ \Carbon\Carbon::parse($item->overtime_date)->format('d/m/Y') }}" readonly>
                                                    </div>
                                                    <label class="col-sm-1 col-form-label">Day Request</label>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" name="days" id="days" value="{{ \Carbon\Carbon::parse($item->overtime_date)->format('l') }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row container_ovt_details">

                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-sm-2 col-form-label">Start Time</label>
                                                    <div class="col-sm-3">
                                                        <input type="text" name="start" id="start" class="form-control" value="{{ \Carbon\Carbon::parse($item->overtime_start)->format('H:i:s') }}" readonly>                  
                                                    </div>
                                                    <label class="col-sm-1 col-form-label">End Time</label>
                                                    <div class="col-sm-3">
                                                        <input type="text" name="end" id="end" class="form-control" value="{{ \Carbon\Carbon::parse($item->overtime_end)->format('H:i:s') }}" readonly>
                                                    </div>
                                                    <label class="col-sm-1 col-form-label">Duration</label>
                                                    <div class="col-sm-2">
                                                        <input type='text' class='form-control' name="durasi" id="durasi" value="{{ \Carbon\Carbon::parse($item->overtime_duration)->format('H:i:s') }}" readonly>
                                                    </div>
                                                </div>
                                                @if ($item->status_id == 10)
                                                    <div class="mb-3 row">
                                                        <label class="col-sm-2 col-form-label">Actual End Time</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="actual_end" id="actual_end" class="form-control" value="@if($item->actual_end != '') {{ \Carbon\Carbon::parse($item->actual_end)->format('H:i:s') }} @else @endif" readonly>                  
                                                        </div>
                                                        <label class="col-sm-2 col-form-label">Actual Duration</label>
                                                        <div class="col-sm-4">
                                                            <input type='text' class='form-control' name="actual_durasi" id="actual_durasi" value="@if($item->actual_duration != '') {{ \Carbon\Carbon::parse($item->actual_duration)->format('H:i:s') }} @else @endif" readonly>
                                                        </div>
                                                    </div>
                                                @else
                                                    
                                                @endif
                                                
                                                <div class="mb-3 row">
                                                    <label class="col-sm-2 col-form-label">User</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="user" id="users" value="{{ $item->overtime_user }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                @if ($item->overtime_status == '1')
                                                    <a href="/overtime/history-ticket/modify/{{ $item->overtime_code }}" class="btn btn-primary">Modify</a>
                                                    <a class="btn btn-danger mt-1" id="btnCancel" data-id="{{ $item->overtime_code }}" style="color: #fff">Cancel Overtime</a>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                @elseif($item->overtime_status == '9')
                                                    <a href="/overtime/history-ticket/modify/{{ $item->overtime_code }}" class="btn btn-primary">Modify</a>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                @elseif ($item->overtime_status == '7')
                                                    <form action="/overtime/history-ticket/request-modify/{{ $item->overtime_code }}" id="form_req_modify{{ $item->overtime_code }}" method="POST">
                                                        {!! csrf_field() !!}
                                                        <input type="hidden" name="end" value="{{ \Carbon\Carbon::parse($item->overtime_end)->format('H:i:s') }}">
                                                        <input type="hidden" name="duration" value="{{ \Carbon\Carbon::parse($item->overtime_duration)->format('H:i:s') }}">
                                                        <input type="hidden" name="tenantPerson" value="{{ $item->tenant_person }}">
                                                        <button type="button" class="btn btn-warning" id="btnReqModify" data-id="{{ $item->overtime_code }}" style="color: #fff">Request Modify</button>
                                                    </form>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    {{-- @elseif($item->overtime_status == '8')
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <a class="btn btn-danger mt-1" href="/overtime/history-ticket/delete/{{ $item->overtime_code }}" onclick="return confirm('Delete this Data?')">Cancel Overtime</a> --}}
                                                @else
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                            @endif
                        @empty
                        <tr>
                            <td colspan="11"><center> Not Found</center></td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
@else
<script>
    window.location = "{{ route('news') }}";
</script>
@endif
@endsection

@push('scripts')
{{ HTML::script('js/overtime.js') }}
@endpush