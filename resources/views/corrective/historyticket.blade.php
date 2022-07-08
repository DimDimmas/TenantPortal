@extends('layouts.crm_main')

@section('content')
@if(empty(Auth::user()))
   <script>
      window.location = "{{ route('logout') }}";
   </script>
@else
    <style>
        .alert .close {
            color: unset !important;
        }
        .btn i{
            margin-right: 0;
        }
        .img-attach{
            display: block
        }
        .form-control:disabled, .form-control[readonly]{
            background-color: #fff !important;
        }
        .form-control{
            color: #333 !important
        }
        .form-control[disabled], fieldset[disabled] .form-control {
        cursor: not-allowed;
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
        .dataTables_filter{
            display: none;
        }
        .dataTables_wrapper .dataTables_length {
        float: right;
        }
        table.dataTable tbody th, table.dataTable tbody td{
            padding: 10px 18px !important;
        }
        /* table.dataTable thead th, table.dataTable thead td {
            padding: 10px 10px !important;
        } */
    </style>

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
    @endif

    <div class="col-sm-12 col-md-12 col-xs-12">
        <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                <h4 class="card-title"><b>Ticket Request History</b></h4>
                </div>
            </div>
            <hr>
            <div class="table-responsive center mt-5">
                <div class="col-md-auto mb-3">
                    <table class="mb-3" border="0">
                        <tbody>
                            <tr>
                                <td>Show Status By</td>
                                <td>
                                <select class="form-control ml-2" name="statusBy" id="statusBy">
                                    <option value="">All</option>
                                    <option value="New">New</option>
                                    <option value="On Progress">On Progress</option>
                                    <option value="Done">Done</option>
                                    <option value="Close">Close</option>
                                </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="cont-table">
                        <table id="tableRequestTicket" class="display mb-2" style="color: #353535">
                            <thead>
                                <tr>
                                    <th style="width: 100px"><center>Action</center></th>
                                    <th hidden></th>
                                    <th class="dt-search">Ticket No</th>
                                    <th class="dt-search">Status</th>
                                    <th class="dt-search">Contact Person</th>
                                    {{-- <th class="dt-search">Tenant Name</th> --}}
                                    <th class="dt-search">Form</th>
                                    <th class="dt-search">Type</th>
                                    <th class="dt-search">Category</th>
                                    <th class="dt-search">Location</th>
                                    <th class="dt-search">Description</th>
                                    <th class="dt-search">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ticket_list as $item)
                                    @if ($item->tenant_id == Auth::user()->tenant_id)
                                        <tr>
                                            @if ($item->status_tenant == '1' && $item->tenant_id == Auth::user()->tenant_id)                                    
                                                <td>
                                                    <center>
                                                    <a class="btn btn-primary btn-sm mt-1" style="float: left; margin:2px;" href="/corrective/history-ticket/show/{{ $item->tenant_ticket_id }}" data-toggle="tooltip" data-placement="left" title="View"><i class="fa fa-search" aria-hidden="true"></i></a>
                                                    {{-- <br> --}}
                                                    <button class="btn btn-warning btn-sm mt-1" id="btnModalImg" 
                                                        data-toggle="modal" data-target="#modalImg"
                                                        data-ticketid="{{ $item->tenant_ticket_id }}"
                                                        data-attach="{{ $item->tenant_ticket_attachment }}" style="float: left; margin:2px;"><i class="fa fa-paperclip" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="View"></i></button>
                                                    {{-- <br> --}}
                                                    <button class="btn btn-danger btn-sm mt-1" style="float: left; margin:2px;" id="btnDelete" data-id="{{ $item->tenant_ticket_id }}"><i class="fa fa-trash delete" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="View"></i></button>
                                                </center>
                                                </td>
                                            @else
                                                <td>
                                                    <center>
                                                        <a class="btn btn-primary btn-sm mt-1" style="float: left; margin:2px;" href="/corrective/history-ticket/show/{{ $item->tenant_ticket_id }}"><i class="fa fa-search" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="View"></i></a>
                                                        {{-- <br> --}}
                                                        <button class="btn btn-warning btn-sm mt-1" style="float: left; margin:2px;" id="btnModalImg" 
                                                            data-toggle="modal" data-target="#modalImg"
                                                            data-ticketid="{{ $item->tenant_ticket_id }}"
                                                            data-attach="{{ $item->tenant_ticket_attachment }}"><i class="fa fa-paperclip" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="View"></i>
                                                        </button>
                                                    </center>
                                                </td>
                                            @endif
                                            <td hidden>1</td>
                                            <td>{{ $item->tenant_ticket_id }}</td>
                                            <td>{{ $item->status_name }}</td>
                                            {{-- <td>{{ $item->tenant_person }}</td> --}}
                                            <td>{{ $item->company_name }}</td>
                                            <td>{{ $item->form_desc }}</td>
                                            <td>
                                                {{ $item->type_desc }}
                                            </td>
                                            <td>{{ $item->category_desc }}</td>
                                            <td>{{ $item->tenant_ticket_location }}</td>
                                            <td>{{ $item->tenant_ticket_description }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tenant_ticket_post)->format('d/m/Y') }}</td>                                
                                            
                                            {{-- </form> --}}
                                        </tr>
                                    @endif
                                @endforeach                    
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal image -->
    <div class="modal fade" id="modalImg" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <img src="" class="form_image" id="post-attach" width="100%">
                {{-- <span id="post-attach"></span> --}}
            </div>
        </div>
        </div>
    </div>

    <!-- Modal show -->
    <div class="modal fade modalshow" id="modalShow"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span>Detail Ticket</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Ticket No</label>
                    <div class="col-sm-10">
                        <input id="post-id" type="text" class="form-control " disabled>
                    </div>               
                </div>        
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">
                        <input id="post-status" type="text" class="form-control " disabled>
                    </div>               
                </div>                      
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Entity</label>
                    <div class="col-sm-10">
                        <input id="post-company_name" type="text" class="form-control " disabled>
                    </div>               
                </div>    
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Form</label>
                    <div class="col-sm-10">
                        <input id="post-form" type="text" class="form-control " disabled>
                    </div>               
                </div>                
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Type</label>
                    <div class="col-sm-10">
                        <input id="post-type" type="text" class="form-control " disabled>
                    </div>
                </div>                
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Category</label>
                    <div class="col-sm-10">
                        <input id="post-category" type="text" class="form-control " disabled>
                    </div>               
                </div>                
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Location</label>
                    <div class="col-sm-10">
                        <input id="post-location" type="text" class="form-control " disabled>
                    </div>               
                </div>                
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10">
                        <input id="post-description" type="text" class="form-control " disabled>
                    </div>               
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Date</label>
                    <div class="col-sm-10">
                        <input id="post-date" type="text" class="form-control " disabled>
                    </div>               
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Attachment</label>
                    <div class="col-sm-10">
                        <img src="" class="form_image img-thumbnail" id="post-img" width="300">
                    </div>               
                </div>
                <button class="btn btn-danger mt-2" href="" style="float: right" id="btnModalConfirm" 
                data-toggle="modal" data-target="#modalConfirm" data-dismiss="modal"
                data-status="7"><i class="fa fa-times-circle" aria-hidden="true"></i> &nbsp; Close Ticket</button>
            </div>
        </div>
        </div>
    </div>

    {{-- modal confirmation --}}
    <div id="confirmModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <span>Confirmation</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <span>Are you sure close this ticket?</span> <span id="post-idclose"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" name="ok_button" id="ok_button" class="btn btn-danger" data-dismiss="modal">OK</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
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
{{ HTML::script('js/corrective.js') }}
@endpush
