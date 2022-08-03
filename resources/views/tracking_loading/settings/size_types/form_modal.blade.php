<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" 
    aria-labelledby="staticBackdropLabel" aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <form action="/tracking-loading/settings/size-types/create-update" method="POST" enctype="multipart/form-data" id="form">
            {{ csrf_field() }}
            {{ method_field('POST') }}
            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="code" id="code" value="">
            <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="staticBackdropLabel">Setting Size Type</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="name">
                            Name&nbsp;<b class="text-danger">*</b>
                        </label>
                        <input type="text" class="form-control form-control-sm" id="name" name="name">
                    </div>

                    <div class="form-group">
                        <label for="description">
                            Description&nbsp; <!-- <b class="text-danger">*</b> -->
                        </label>
                        <textarea class="form-control form-control-sm" id="description" name="description" rows="3"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>