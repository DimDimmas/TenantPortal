<script>  
  toastr.options = {
    "closeButton" : true,
    "progressBar" : true
  }

  function getDataTable(dateSelected){
    $('#tableList').DataTable({
      processing: true,
      serverSide: true,
      ordering: false,
      ajax: {
        url: '/tracking-loading/not-scan-out/listHistory',
        method: 'GET',
        data: {dateSelected:dateSelected}
      },
      columns: [
        { data: 'action' },
        { data:'img_capture'} ,
        { data:'img_ktp'} ,
        { data:'scan_in'} ,
        { data:'scan_out'} ,
        { data:'difference'} ,
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
</script>