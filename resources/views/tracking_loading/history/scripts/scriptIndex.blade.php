<script>  
  toastr.options = {
    "closeButton" : true,
    "progressBar" : true
  }

  function getDataTable(dateSelected){
    $('#tableList').DataTable({
      order: [[0, 'desc']],
      processing: true,
      serverSide: true,
      ordering: false,
      ajax: {
        url: '/tracking-loading/listHistory',
        method: 'GET',
        data: {dateSelected:dateSelected}
      },
      columns: [
        {data: 'id', name: 'id', visible: false},
        {
          data : null,
          name: 'action',
          orderable: false,
          searchable: false,
          class : 'text-center',
          render: function(data, type, row, meta) {
            let rowData = {
              id : row.id,
              identitfier : row.identifier ? row.identifier.replace(/^\s+|\s+$/gm,'') : null,
              entity_project : row.entity_project ? row.entity_project.replace(/^\s+|\s+$/gm,'') : null,
              entity_name : row.entity_name ? row.entity_name.replace(/^\s+|\s+$/gm,'') : null,
              project_no : row.project_no ? row.project_no.replace(/^\s+|\s+$/gm,'') : null,
              project_name : row.project_name ? row.project_name.replace(/^\s+|\s+$/gm,'') : null,
              debtor_acct : row.debtor_acct ? row.debtor_acct.replace(/^\s+|\s+$/gm,'') : null,
              debtor_name : row.debtor_name ? row.debtor_name.replace(/^\s+|\s+$/gm,'') : null,
              plate_area : row.plate_area ? row.plate_area.replace(/^\s+|\s+$/gm,'') : null,
              police_no : row.police_no ? row.police_no.replace(/^\s+|\s+$/gm,'') : null,
              identity_no : row.identity_no ? row.identity_no.replace(/^\s+|\s+$/gm,'') : null,
              identity_name : row.identity_name ? row.identity_name.replace(/^\s+|\s+$/gm,'') : null,
              scan_out : row.scan_out ? row.scan_out.replace(/^\s+|\s+$/gm,'') : null,
            }
            let dataDecrypt = btoa(unescape(encodeURIComponent(JSON.stringify(row))));
            let btn = `
                <button class="btn btn-sm btn-success" onclick="showEditModal('${dataDecrypt}')">
                  <i class="fa fa-pencil"></i>
                </button>
              `;
            // if row.scan_out !== null {
            //   btn += `
            //     <button class="btn btn-sm btn-success" onclick="showEditModal('${btoa(JSON.stringify(row))}')">
            //       <i class="fa fa-pencil"></i>
            //     </button>
            //   `;
            // }

            return btn;
            
          },
        },
        { data:'img_capture'} ,
        { data : 'police_no' },
        { data:'img_ktp'} ,
        { data : 'identity_no' },
        { data : 'identity_name' },
        { data:'scan_in'} ,
        { data:'scan_out'} ,
        { data:'difference', searchable: false, orderable: false} ,
        { data:'type'} ,
      ],
    });  
  }
    
  $('#date_filter').daterangepicker({
    startDate: new Date(),
    locale: {
      format: 'DD/MM/YYYY',
    },
    function (start) {
      startdate = start.format('DD/MM/YYYY')
    }
    //    autoUpdateInput: false
  }).change(function(){
    $('#tableList').DataTable().clear().destroy();
    var dateNow = $('#date_filter').val();
    getDataTable(dateNow)  
  });

  var dateNow = $('#date_filter').val();
  getDataTable(dateNow)

  $('#btnGeneratePdf').click((e) => {
    e.preventDefault();
    var dateSelected = $('#date_filter').val();
    var url = "/tracking-loading/pdf?dateSelected=" + dateSelected;
    toastr.success("Success.. success generate PDF");
    window.open(url, "_blank");
  })

  $('#btnGenerateExcel').click((e) => {
    e.preventDefault();
    var dateSelected = $('#date_filter').val();
    var url = "/tracking-loading/excel?dateSelected=" + dateSelected;
    toastr.success("Success.. success generate Excel");
    window.open(url, "_blank");
  })

  function showImage(elmInp) {
    $(".imageShow").attr("src", $(elmInp).attr("src"));
    $("#showImage").modal("show");
  }

  $("#close").click(function(){
    $("#showImage").modal("hide");
  });

  function showEditModal(data) {
    // let dataDecrypt = atob(data)
    let dataDecrypt = decodeURIComponent(escape(window.atob(data)));
    let row = JSON.parse(dataDecrypt);
    let form = $("#form");
    
    $("#modalForm").modal('show');

    form.find("input[name='id']").val(row.id);
    form.find("input[name='identifier']").val(row.identifier);
    form.find("input[name='entity_project']").val(row.entity_project);
    form.find("input[name='project_no']").val(row.project_no);
    form.find("input[name='debtor_acct']").val(row.debtor_acct);
    form.find("input[name='police_no']").val(row.police_no);
    form.find("input[name='identity_no']").val(row.identity_no);
    form.find("input[name='identity_name']").val(row.identity_name);
  }

  $("#form").submit((e) => {
    e.preventDefault();
    let form = $("#form");
    let formData = new FormData(form[0]);
    let url = form.attr('action');
    
    $(".form-control").removeClass('is-invalid');
    $(".custom-file-input").removeClass('is-invalid');
    // console.log(url);
    
    $("#form").find('.error').remove();

    $.ajax({
        url: url,
        "method": "POST",
        cache: false,
        processData: false,
        contentType: false,
        data: formData,
        success: (result) => {
            if(result.error) {
                if(result.code == 422){
                    // toastr.error(result.message);
                    $.each(JSON.parse(result.errors), function (key, value) {
                      toastr.error(value[0]);
                      $('#' + key + "_form")
                          .closest('.form-control')
                          .addClass('is-invalid')
                      // $('#' + key + "_form")
                      //     .closest('.form-group')
                      //     .append('<p class="error invalid-feedback">' + value[0] + '</p>')
                      $('#' + key + "_form")
                        .closest('.form-group')
                        .append('<span class="error invalid-feedback">' + value + '</span>')
                    })
                    
                } else {
                    toastr.error(result.message);
                }
                return false;
            }
            toastr.success(result.message);
            form.trigger('reset');
            $(".select2-export").val('').trigger('change');
            $('#tableList').DataTable().ajax.reload();
            $("#modalForm").modal('hide');
        },
        error: (xhr, ajaxOptions, thrownError) => {
            toastr.error(thrownError);
        }
    });
  });


</script>