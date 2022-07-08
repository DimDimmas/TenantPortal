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
            margin-right: 0
        }
        .form-control[disabled], fieldset[disabled] .form-control {
            cursor: not-allowed;
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
        .swal-wide{
            width: 20% !important;
        }
    </style>

    <div class="col-sm-12 col-md-12 col-xs-12">
        <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                <h4 class="card-title"><b>Ticket Request</b></h4>
                </div>
            </div>
            <hr>

            <div class="table-responsive center">
                <div class="col-md-6" style="float: left">
                    <form action="{{ route('req-ticket.store') }}" method="post" enctype="multipart/form-data" id="form_corrective">
                    {!! csrf_field() !!}
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Contact Person</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tenantName" value="{{ Auth::user()->tenant_person }}" readonly disabled>
                            <input type="hidden" name="tenantId" value="{{ Auth::user()->tenant_id }}">
                            <input type="hidden" name="tenantCode" value="TN-{{ Auth::user()->tenant_code }}">
                            <input type="hidden" name="entityProject" value="{{ Auth::user()->entity_project }}">
                            <input type="hidden" name="entityCd" value="{{ Auth::user()->entity_cd }}">
                            <input type="hidden" name="projectNo" value="{{ Auth::user()->project_no }}">
                        </div>               
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Form</label>
                        <div class="col-sm-10">
                            <select class="form-control select2" name="formId" id="formId"  oninvalid="this.setCustomValidity('Form belum dipilih.')" onchange="this.setCustomValidity('')" required>
                                <option value="">-</option>
                                @forelse ($form as $key => $value)
                                    <option value="{{ $value->form_id }}">{{ $value->form_desc }}</option>
                                @empty
                                    Not Found
                                @endforelse
                            </select>
                        </div>                    
                    </div>
                    {{-- <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Category</label>
                        <div class="col-sm-10">
                            <select class="select2 form-control" name="cateId" id="cateId"  oninvalid="this.setCustomValidity('Kategori belum dipilih.')" onchange="this.setCustomValidity('')" required>
                                <option value="">-</option>
                                @forelse ($type_id as $types)
                                <option value="{{ $types->type_id }}">{{ $types->type_desc }}</option>
                                @empty
                                Not Found
                                @endforelse
                            </select>
                        </div>
                    </div> --}}
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Location</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tenantTicketLocation" id="location" placeholder="Location"  oninvalid="this.setCustomValidity('Lokasi belum diisi.')" onchange="this.setCustomValidity('')" required>
                            <small id="tenantTicketLocation" class="form-text text-muted">Lokasi terjadinya complaint</small>
                        </div>                    
                    </div>
                </div>
                <div class="col-md-6" style="float: left">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="tenantTicketDesc" id="description" cols="10" rows="3" style="line-height: 2;" placeholder="Description"  oninvalid="this.setCustomValidity('Deskripsi belum diisi.')" onchange="this.setCustomValidity('')" required></textarea>
                            <small id="tenantTicketLocation" class="form-text text-muted">Deskripsi complaint yang terjadi</small>
                        </div>                    
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Attachment</label>
                        <div class="col-sm-10">
                            <input type="file" name="tenantTicketAttachment" accept="image/*" id="tenantTicketAttachment" class="form-control" style="border-style: dashed; padding: 0px 10px" oninvalid="this.setCustomValidity('Photo belum diisi.')" onchange="this.setCustomValidity('')" required>
                        </div>                    
                    </div>
                    <button type="button" id="btnSubmit" class="btn btn-success mb-5" style="float: right; background-color: #26B99A; border-radius: 0; border: 0"><i class="fa fa-check"></i> Send</button>
                    <a href="{{ route('news') }}" class="btn btn-danger mb-5" style="float: right; background-color: #d9534f; border-radius: 0; border: 0"><i class="fa fa-times"></i> Close</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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