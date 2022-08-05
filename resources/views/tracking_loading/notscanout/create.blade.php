@extends('layouts.crm_main')

@section('content')
<style>
  .btn{
    /* border-radius: 0px; */
    text-align: center;
  }
  @media only screen and (max-width: 600px) and (max-width: 768px){
    #btnGeneratePdf, #btnGenerateExcel{
      width: 100%;
    }
  }
</style>
<div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
  <div class="iq-card">
    <div class="iq-card-header d-flex justify-content-between">
      <div class="iq-header-title mt-3">
      <h4 class="card-title"><b>Create BAK (Berita Acara Kehilangan)</i></b></h4>
      </div>
    </div>
    <hr>
    <div class="table-responsive center">
      <div class="col-xs-auto col-sm-auto col-md-auto col-lg-auto mb-3">
        <form id="formRequest">
          <div class="col-xs-auto col-sm-6 col-md-6 col-lg-6" style="float: left !important;">
            <div class="row mb-3">
              <label class="col-xs-auto col-sm-2 col-md-2 col-lg-2 col-form-label">BAK No. <span class="text-danger">*</span></label>
              <div class="col-xs-auto col-sm-10 col-md-10 col-lg-10">
                <input type="text" class="form-control" name="bak_no" id="bak_no" value="{{ $autonumber }}" readonly>
                <input type="hidden" class="form-control" name="identifier" id="identifier" value="{{ $identifier[1] }}" readonly hidden>
                <input type="hidden" class="form-control" name="id_visit_track" id="id_visit_track" value="{{ $id[1] }}" readonly hidden>
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-xs-auto col-sm-2 col-md-2 col-lg-2 col-form-label">Entity <span class="text-danger">*</span></label>
              <div class="col-xs-auto col-sm-10 col-md-10 col-lg-10">
                <input type="text" class="form-control" value="{{ $entity->entity_name }}" readonly disabled>
                <input type="hidden" class="form-control" name="entity_project" id="entity_project" value="{{ $user->entity_project }}" readonly>
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-xs-auto col-sm-2 col-md-2 col-lg-2 col-form-label">Project <span class="text-danger">*</span></label>
              <div class="col-xs-auto col-sm-10 col-md-10 col-lg-10">
                <input type="text" class="form-control" value="{{ $project->descs }}" readonly disabled>
                <input type="hidden" class="form-control" name="project_no" id="project_no" value="{{ $user->project_no }}" readonly>
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-xs-auto col-sm-2 col-md-2 col-lg-2 col-form-label">Debtor <span class="text-danger">*</span></label>
              <div class="col-xs-auto col-sm-10 col-md-10 col-lg-10">
                <input type="text" class="form-control" value="{{ $debtor->name }}" readonly disabled>
                <input type="hidden" class="form-control" name="debtor_acct" id="debtor_acct" value="{{ $user->tenant_code }}" readonly>
              </div>
            </div>
          </div>

          <div class="col-xs-auto col-sm-6 col-md-6 col-lg-6" style="float: left !important;">
            <div class="row mb-3">
              <label class="col-xs-auto col-sm-2 col-md-2 col-lg-2 col-form-label">Police No. <span class="text-danger">*</span></label>
              <div class="col-xs-auto col-sm-10 col-md-10 col-lg-10">
                <input type="text" class="form-control" name="police_no" placeholder="Police No" oninput="this.value = this.value.toUpperCase()">
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-xs-auto col-sm-2 col-md-2 col-lg-2 col-form-label">Identity No. <span class="text-danger">*</span></label>
              <div class="col-xs-auto col-sm-10 col-md-10 col-lg-10">
                <input type="text" class="form-control" name="identity_no" placeholder="Identity No" oninput="this.value = this.value.toUpperCase()">
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-xs-auto col-sm-2 col-md-2 col-lg-2 col-form-label">Identity Name <span class="text-danger">*</span></label>
              <div class="col-xs-auto col-sm-10 col-md-10 col-lg-10">
                <input type="text" class="form-control" name="identity_name" placeholder="Identity Name" oninput="this.value = this.value.toUpperCase()">
              </div>
            </div>
          </div>
        </form>

        <div class="clearfix"></div>
        <hr>

        <div class="col-xs-auto col-sm-auto col-md-auto col-lg-auto">
          <div class="pull-right mb-5">
            <button type="button" id="btnSubmit" class="btn btn-success" style="float: right; background-color: #26B99A; border-radius: 0; border: 0"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
            <a onclick="history.back()" class="btn btn-danger" style="float: right; background-color: #d9534f; border-radius: 0; border: 0; color: #fff;"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
@include('tracking_loading.notscanout.scripts.scriptCreate')
@endpush