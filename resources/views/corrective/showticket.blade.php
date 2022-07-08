@extends('layouts.crm_main')

@section('content')
@if(empty(Auth::user()))
   <script>
      window.location = "{{ route('logout') }}";
   </script>
@else
    @if ($list->tenant_id != Auth::user()->tenant_id)
        <script>
            window.location = "{{ route('history_ticket') }}";
        </script>
    @else
        
    <style>
        .form-control:disabled, .form-control[readonly]{
            background-color: #fff !important;
        }
        .form-control{
            color: #333 !important
        }
        .form-control[disabled], fieldset[disabled] .form-control {
        cursor: not-allowed;
        }
    </style>

    <div class="col-sm-12 col-md-12 col-xs-12">
      <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
          <div class="iq-header-title">
            <h4 class="card-title"><b>Ticket Responses</b></h4>
          </div>
        </div>
        <hr>

        <div class="table-responsive center">
          <div class="col-md-12">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Ticket No</th>
                  <th>Status</th>
                  <th>Request Date</th>
                  <th>Engineer Response Time</th>
                  <th>Engineer Response Duration</th>
                  <th>Done Time</th>
                  <th>Close In</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{{ $code }}</td>
                  <td>{{ $list->status_name }}</td>
                  <td>{{ \Carbon\Carbon::parse($list->tenant_ticket_post)->format('d/m/Y')}}</td>
                  <td>{{ \Carbon\Carbon::parse($get_ticket_response->ResponTime)->format('d/m/Y H:i:s') }}</td>
                  <td>{{ $get_ticket_response->DurationRespon }} Hour</td>
                  <td>{{ \Carbon\Carbon::parse($get_ticket_response->DoneTime)->format('d/m/Y H:i:s') }}</td>
                  <td>{{ $get_ticket_response->DurationClose }} Hour</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-12 col-md-12 col-xs-12">
        <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                  <h4 class="card-title"><b>Ticket Description</b></h4>
                </div>
            </div>
            <hr>

            <div class="table-responsive center">
                <div class="col-md-6 float-left">
                  <div class="mb-3 row">
                      <label class="col-sm-2 col-form-label">Contact Person</label>
                      <div class="col-sm-10">
                          <input type="text" class="form-control" name="contact_person" value="{{ $list->tenant_person }}" readonly>
                      </div>               
                  </div>
                  <div class="mb-3 row">
                      <label class="col-sm-2 col-form-label">Tenant</label>
                      <div class="col-sm-10">
                          <input type="text" class="form-control" name="tenant" value="{{ $list->company_name }}" readonly>
                      </div>               
                  </div>
                  <div class="mb-3 row">
                      <label class="col-sm-2 col-form-label">Form</label>
                      <div class="col-sm-10">
                          <input type="text" class="form-control" name="form" value="{{ $list->form_desc }}" readonly>
                      </div>               
                  </div>                
                  <div class="mb-3 row">
                      <label class="col-sm-2 col-form-label">Type</label>
                      <div class="col-sm-10">
                          <input type="text" class="form-control" name="type" value="{{ $list->type_desc }}" readonly>
                      </div>
                  </div>                
                  <div class="mb-3 row">
                      <label class="col-sm-2 col-form-label">Category</label>
                      <div class="col-sm-10">
                          <input type="text" class="form-control" name="category" value="{{ $list->category_desc }}" readonly>
                      </div>               
                  </div>
                </div>
                <div class="col-md-6 float-left">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Location</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="location" value="{{ $list->tenant_ticket_location }}" readonly>
                        </div>               
                    </div>                
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-10">
                            {{-- <input type="text" class="form-control " value="{{ $list->tenant_ticket_description }}" disabled> --}}
                            <textarea class="form-control" name="description" readonly>{{ $list->tenant_ticket_description }}</textarea>
                        </div>               
                    </div>
                    <div class="mb-5 row">
                        <label class="col-sm-2 col-form-label">Attachment</label>
                        <div class="col-sm-10">
                            <img src="/img/bms/photo/{{ $list->tenant_ticket_attachment }}" name="attachment" class="form_image img-thumbnail" alt="{{ $list->tenant_ticket_attachment }}" width="300">
                        </div>               
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-12 col-xs-12">
        <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                <h4 class="card-title"><b>Responses Details</b></h4>
                </div>
            </div>
            <hr>

            <div class="table-responsive center">
                <div class="col-md-12">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Username PIC</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" 
                            @if ($list->status_name == "New")
                                value=""
                            @else
                                @if (count($get_engineer_2) < 1)
                                    value="@foreach ($get_engineer_1 as $get_engineer) {{ $get_engineer->emp_name }}, @endforeach"
                                @else
                                    value="@foreach ($get_engineer_2 as $get_engineer) {{ $get_engineer->emp_name }}, @endforeach"
                                @endif
                            @endif
                            readonly>
                        </div>               
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Remarks</label>
                        <div class="col-sm-10">
                            <div class="iq-card-body">
                                <ul class="iq-timeline">
                                    @forelse ($activity as $item)
                                        <li>
                                          <div class="timeline-dots"></div>
                                          <div class="col-md-12">
                                            <h6 class="pull-left mb-1">{{ $item->description }}</h6>
                                            <small class="pull-right mt-1">{{ $item->engineering_username }}<br>{{ $item->time_taken }} | {{ \Carbon\Carbon::parse($item->created_date)->format('d/m/Y') }}</small>
                                          </div>
                                          <div class="col-md-12 row">
                                            <div class="d-inline-block w-50">
                                              Image Before 
                                              <br>
                                              @if ($item->attachment != "")
                                                  @foreach (explode(';;', $item->attachment) as $img_b)
                                                  <button class="btn btn-warning mt-1" id="btnModalImgBefore" 
                                                  data-toggle="modal" data-target="#modalImgBefore"
                                                  data-attachment-b="{{ $img_b }}"><i class="fa fa-paperclip" aria-hidden="true"></i>Image</button>
                                                  @endforeach
                                              @endif
                                            </div>
                                            <div class="d-inline-block w-50">
                                                Image After 
                                                <br>
                                                @if ($item->attachment_after != "")
                                                    @foreach (explode(';;', $item->attachment_after) as $img_a)
                                                    <button class="btn btn-warning mt-1" id="btnModalImgAfter" 
                                                    data-toggle="modal" data-target="#modalImgAfter"
                                                    data-attachment-a="{{ $img_a }}"><i class="fa fa-paperclip" aria-hidden="true"></i>Image</button>
                                                    @endforeach
                                                @endif
                                            </div>
                                          </div>
                                        </li>
                                    @empty
                                        
                                    @endforelse                               
                                </ul>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('history_ticket') }}" class="btn btn-danger mb-5" style="float: left; background-color: #d9534f; border-radius: 0; border: 0"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back to History</a>
                    <form action="/corrective/history-ticket/show/update/{{ $code }}" id="form_close" method="POST" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                    <input type="hidden" value="{{ $list->tenant_ticket_id }}" id="ticket-id" name="ticket-id">
                    @if ($list->status_tenant == '10')
                        {{-- <button hidden type="submit" class="btn btn-success mb-5" style="float: right; border-radius: 0; border: 0" 
                            onclick="return confirm('Are you sure close this ticket?')">
                            <i class="fa fa-times-circle" aria-hidden="true"></i> &nbsp; Close Ticket
                        </button> --}}
                    @else
                        <button type="button" id="btnClose" class="btn btn-success mb-5" style="float: right; border-radius: 0; border: 0">
                            <i class="fa fa-times-circle" aria-hidden="true"></i> &nbsp; Close Ticket
                        </button>
                    @endif
                    </form>
                    {{-- <a href="" class="btn mb-5" style="background-color: #827af3;;float: right; border-radius: 0; border: 0"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> &nbsp; Create Form BAP</a>
                    <a href="/corrective/history-ticket/create-form-bak/{{ $code }}" class="btn btn-warning mb-5" style="float: right; border-radius: 0; border: 0"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> &nbsp; Create Form BAK</a> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal image -->
    <div class="modal fade" id="modalImgBefore" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <img src="" class="form_image" id="post-attach-b" width="100%">
                {{-- <span id="post-attach"></span> --}}
            </div>
        </div>
        </div>
    </div>

    <!-- Modal image -->
    <div class="modal fade" id="modalImgAfter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <img src="" class="form_image" id="post-attach-a" width="100%">
                {{-- <span id="post-attach"></span> --}}
            </div>
        </div>
        </div>
    </div>
    @endif
@endif
@endsection

<script src="{{ asset('moa/js/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).on('click', '#btnModalImgBefore', function () { 
        var attach_b = $(this).data('attachment-b');

        $('#post-attach-b').attr('src', '/img/bms/photo/'+attach_b);
    });
    $(document).on('click', '#btnModalImgAfter', function () { 
        var attach_a = $(this).data('attachment-a');

        $('#post-attach-a').attr('src', '/img/bms/photo/'+attach_a);
    });
    var msg = '{{Session::get('alert')}}';
    var exist = '{{Session::has('alert')}}';
    if(exist){
      alert(msg);
    }
</script>

@push('scripts')
{{ HTML::script('js/corrective.js') }}
@endpush