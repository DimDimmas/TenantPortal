@extends('layouts.crm_main')

@section('content')
<style>
  .btn{
    border-radius: 0px;
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
      <h4 class="card-title"><b>Tracking Loading Setting - <i>{{ $tenant->company_name }}</i></b></h4>
      </div>
    </div>
    <hr>
    <div class="table-responsive center mt-5">
      <div class="col-xs-auto col-sm-auto col-md-auto col-lg-auto mb-3">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-5 pull-right">          
          <div class="pull-left">
            {{-- <table>              
              <tr>
                <td>Show by Date</td>
                <td></td>
                <td>
                  <input type="text" class="form-control m-2" name="date_filter" id="date_filter">
                </td>
              </tr>
            </table> --}}
          </div>
          <div class="pull-right">
            <button type="button" class="btn btn-success" id="btnTambah" onclick="openModal();"><i class="fa fa-plus" aria-hidden="true"></i> &nbsp; New Setting</button>
            <button type="button" class="btn btn-primary" id="btnMstSizeTypes"><i class="fa fa-meetup" aria-hidden="true"></i>&nbsp; Master Size Type</button>
          </div> 
          <div class="clearfix"></div>
        </div>
      </div>
      <div class="table-responsive">
        <table id="table" class="table table-striped table-hover display mb-2" style="color: #353535;">
            <thead>
              <tr>
                <th scope="col">Id</th>
                <th scope="col">Type</th>
                <th scope="col">Size Type</th>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Status</th>
                <th scope="col">Value</th>
                <th scope="col">#</th>
              </tr>
            </thead>
        </table>
      </div>
    </div>
  </div>
</div>
@include('tracking_loading.settings.form_modal')
@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>  
    toastr.options = {
      "closeButton" : true,
      "progressBar" : true
    }
  
    let table = $("#table").DataTable({
      "processing": true,
      "serverSide": true,
      "responsive": true,
      "order": [[ 0, "DESC" ]],
      "ajax":{
        url: '/tracking-loading/settings/datatable',
          "dataType": "json",
          "type": "POST",
          "data":{ _token: "{{csrf_token()}}"}
      },
      columns: [
        { data:'id'} ,
        { data:'type'} ,
        { data:'size_type' },
        { data:'name'} ,
        { data:'description'} ,
        { data:'status'} ,
        { data:'value'} ,
        { data:'action'} ,
      ],
      "columnDefs": [
          {
              "targets": [0],
              "orderable": true,
              "searchable": false,
              "visible": false,
          },
          {
              "targets": [7],
              "orderable": false,
              "searchable": false,
              "visible": true,
          },
      ]
    });


    $("#form").submit(function(e) {
      e.preventDefault();
      // $("#loading").show();
      submitAjax("#form");
    });

    function openModal() {
      $('#form')[0].reset();
      $("#id").val("");
      $("#identifier").val("");
      $('#staticBackdrop').modal('show');
    }

    function update(data) {
      let row = JSON.parse(data);
      $("#id").val(row.id);
      $("#type").val(row.type);
      $("#bm_visit_track_mst_size_type_id").val(row.bm_visit_track_mst_size_type_id);
      $("#name").val(row.name);
      $("#description").val(row.description);
      $("#value").val(row.value);
      $("#status").val(row.status);
      $('#staticBackdrop').modal('show');
    }

    $(document).on("click", ".sw-delete", function(){
        let id = $(this).data('id');
        
        Swal.fire({
          title: 'Do you want to delete this?',
          showDenyButton: true,
          showCancelButton: false,
          confirmButtonText: 'Delete',
          denyButtonText: `Cancel`,
        }).then((result) => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            deleteData(id);
          } else if (result.isDenied) {
            // Swal.fire('Changes are not saved', '', 'info')
          }
        });
    });

    function deleteData(id) {
      if(id == null || id == '' || id == undefined){
        toastr.error("Please select data first");
        return false;
      }
      $.ajax({
        url: '/tracking-loading/settings/'+id,
        type: 'DELETE',
        data: {
          _token: "{{csrf_token()}}",
          id: id
        },
        success: function(response) {
          if(response.error) {
            toastr.error(response.message);
            return false;
          }
          toastr.success(response.message);
          table.ajax.reload(null, false);
        }
      });
    }

    $("#btnMstSizeTypes").on("click", function(){
      window.location.href = "/tracking-loading/settings/size-types"
    });

  </script>
@endpush