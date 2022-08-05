<!-- Modal Form Attendance -->
<div class="row">
    <div class="col-sm-12">
      <div id="modalForm" class="modal fade" role="dialog"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Form Tracking Loading</h5>
            </div>
            <form action="/tracking-loading/create-update" method="POST" class="form-horizontal" id="form">
               {!! csrf_field() !!}
               {!! method_field("POST") !!}
               <input type="hidden" name="id" value="">
               <input type="hidden" name="identifier" value="">
                <input type="hidden" name="entity_project" value="">
                <input type="hidden" name="project_no" value="">
                <input type="hidden" name="debtor_acct" value="">
                <div class="modal-body">

                    <div class="form-group">
                        <label>Police No&nbsp;:</label>
                        <input type="text" class="form-control" name="police_no" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label>Identity No&nbsp;:</label>
                        <input type="text" class="form-control" name="identity_no" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label>Identity Name&nbsp;:</label>
                        <input type="text" class="form-control" name="identity_name" autocomplete="off">
                    </div>
    
                </div>
                    
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Save
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" 
                        data-dismiss="modal"
                    ><i class="fa fa-times"></i>&nbsp;Close</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</div>