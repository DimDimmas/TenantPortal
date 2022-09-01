<script>
  $(document).ready(function(){

    $('.select2').select2({
      allowClear: true,
    })
    
    $('#filter_month').on('change', function(){
      $('#tableList').DataTable().destroy();
      table()
    })

    $('#filter_year').change(function(){
      $('#tableList').DataTable().destroy();
      table()
    })

    function table(){
      $('#tableList').DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        ajax: {
          url: '/invoice/listData',
          method: 'GET',
          data: {
            month: $('#filter_month :selected').val(),
            year: $('#filter_year :selected').val(),
          }
        },
        columns: [
          { data: 'id', sClass: 'text-center'},
          { data: 'invoice_no'},
          { data: 'description'},
          { data: 'mbase_amt', sClass: 'text-right'},
          { data: 'sender_date'},
          { data: 'receipt_date'},
        ],
        "columnDefs": [
          {
            "targets": 0,
            "orderable": false,
            "searchable": false,
            "visible": true,
          }
        ],
        'createdRow': function( row, data, dataIndex ) {
            $(row)
            .attr('data-invoice_no', data.invoice_no)
            .attr('data-receipt_no', data.receipt_no)
            .attr('data-entity_project', data.entity_project)
            .attr('data-project_no', data.project_no)
            .attr('data-debtor_acct', data.debtor_acct)
            .attr('data-trx_amt', data.mbase_amt);
        },
        footerCallback: function(row, data, start, end, display){
          var api = this.api();

          // Remove the formatting to get integer data for summation
          var intVal = function (i) {
            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
          };

          // computing column Total of the complete result 
          var totInvAmt = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        $( api.column( 0 ).footer() ).html('Total');
        $( api.column( 3 ).footer() ).html(parseInt(totInvAmt).toLocaleString()+'.00');
        }
      })
    }
    table()
    
    $('#tableList').on('click', 'tbody tr', function(){
      var entity_project = $(this).data('entity_project');
      var project_no = $(this).data('project_no');
      var debtor_acct = $(this).data('debtor_acct');
      var receipt_no = $(this).data('receipt_no');
      var invoice_no = $(this).data('invoice_no');
      var trx_amt = $(this).data('trx_amt');

      $('#modalDetail').modal('show');
      getDataModal(entity_project, project_no, debtor_acct, receipt_no, invoice_no, trx_amt)
    })

    function getDataModal(entity_project, project_no, debtor_acct, receipt_no, invoice_no, trx_amt){
      $.ajax({
        type: "POST",
        url: "/invoice/getDataModal",
        data: {
          "entity_project":entity_project,
          "project_no":project_no,
          "debtor_acct":debtor_acct,
          "receipt_no":receipt_no,
          "invoice_no":invoice_no,
          "trx_amt":trx_amt,
          "_token": "{{ csrf_token() }}"
        },
        dataType: "json",
        success: function (response) {
          var data = response.data;

          $('#invoice_no').val(data.invoice_no);
          $('#description').val(data.description);
          $('#invoice_amount').val(data.invoice_amount);
          $('#balance').val(data.balance);

          $('#delivery_no').val(data.delivery_no);
          $('#delivery_date').val(data.delivery_date);
          $('#delivery_name').val(data.delivery_name);
          $('#receiver_date').val(data.receiver_date);
          $('#receiver_name').val(data.receiver_name);
          $('#due_date').val(data.due_date);

          var file = response.file;
          $('#frame-attachment').attr('src', file.file_name);
          console.log(file.file_name);
        }
      });
    }

  })
</script>