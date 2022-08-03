<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" 
    aria-labelledby="staticBackdropLabel" aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <form action="/tracking-loading/settings" method="POST" enctype="multipart/form-data" id="form">
            {{ csrf_field() }}
            {{ method_field('POST') }}
            <input type="hidden" name="id" id="id" value="">
            <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="staticBackdropLabel">Setting</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="type">
                            Type&nbsp;<b class="text-danger">*</b>
                        </label>
                        <select class="form-control form-control-sm" id="type" name="type">
                            @foreach ($types as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="type">
                            Size Type&nbsp;<b class="text-danger">*</b>
                        </label>
                        <select class="form-control form-control-sm" id="bm_visit_track_mst_size_type_id" 
                            name="bm_visit_track_mst_size_type_id"
                        >
                            @foreach ($sizeTypes as $key => $value)
                                <option value="{{ $value->id }}">{{ ucwords($value->name) }}</option>
                            @endforeach
                        </select>
                    </div>

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

                    <div class="form-group">
                        <label for="status">
                            Status&nbsp;<b class="text-danger">*</b>
                        </label>
                        <select class="form-control form-control-sm" id="status" name="status">
                            @foreach ($statuses as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="value">
                            Value&nbsp;<!-- <b class="text-danger">*</b> -->
                        </label>
                        <input type="number" class="form-control form-control-sm" id="value" name="value"
                            min="0" max="999999" oninput="this.value|=0" value="999999" pattern="[0-9]{6}"
                            onkeydown="return ( event.ctrlKey || event.altKey 
                            || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) 
                            || (95<event.keyCode && event.keyCode<106)
                            || (event.keyCode==8) || (event.keyCode==9) 
                            || (event.keyCode>34 && event.keyCode<40) 
                            || (event.keyCode==46) )"
                        >
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