    $(document).ready(function () {
        // table = $('#datatable').DataTable({
        //     bPaginate: true,
        //     bLengthChange: false,
        //     bFilter: true,
        //     bInfo: true,
        //     processing: true,
        //     serverSide: true,
        //     ordering: false,
        //     pageLength: 10,
        //     fixedHeader: true,
        //     // lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        //     search: {
        //         regex: true
        //     },
        //     ajax: {
        //         url : '/meter/grid-confirm',
        //         type: "POST",
        //         data : {
        //             _token : $('meta[name="csrf-token"]').attr('content')
        //         }
        //     },
        //     columns: [
        //         { data : 'entity' },
        //         {data : 'project' },
        //         { data : 'panel' },
        //         { data : 'curr_read_high', className: "text-right" },
        //         { data : 'curr_read', className: "text-right" },
        //         { data : 'action', className: "text-center" },
        //     ],
        //     // createdRow: (row, data, dataIndex, cells) => {
        //     //     var cssColor = [
        //     //         ['I', {"background-color": "#5ff037", "color": "black"}, 'Invoice'],
        //     //         ['P', {"background-color": "#c4ff41", "color": "black"}, 'Posting'],
        //     //         ['U', {"background-color": "#f9ff41", "color": "black"}, 'Unposting'],
        //     //         ['V', {"background-color": "#f2a35c", "color": "black"}, 'Verified'],
        //     //         ['H', {"background-color": "#72DCE1", "color": "black"}, 'Hold'],
        //     //         ['S', {"background-color": "#ff8c8c", "color": "black"}, 'Submit'],
        //     //     ];
        //     //     $.map(cssColor, function(n, i) {
        //     //             if(data['status_live'] == n[0] || data['status_live'] == n[2]){
        //     //                 $(cells[11]).css(n[1])
        //     //             }
        //     //     })
        //     // }
        // });
        var oldRowIndex;
        var opt = $("#datareading");
        var dg = opt.datagrid({
            url: '/meter/grid-confirm',
            queryParams: {
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            pagination: true,
            clientPaging: false,
            remoteFilter: true,
            rownumbers: true,
            fitColumns: true,
            onSelect: function (rowIndex) {
                if (oldRowIndex == rowIndex) {
                    opt.datagrid('clearSelections', oldRowIndex);
                }
                var selectRow = opt.datagrid('getSelected');
                oldRowIndex = opt.datagrid('getRowIndex', selectRow);
                setFormConfirmation(selectRow);
            },
            columns: [[
                {field:'entity_name',title:'Entity', halign: 'center'},
                {field:'descs',title:'Project', halign: 'center', width:100},
                {field:'panel',title:'Panel', halign: 'center', width:100,priority:1},
                {field:'meter_id',title:'Meter ID',  width:80, halign: 'center', priority:4},
                {field:'type',title:'Type', halign: 'center', width:100,
                formatter: function(value,row,index){
                    if (row.type == 'E'){
                        return 'Electricity';
                    } else {
                        return 'Water';
                    }
                }}, //$.number( 1234.5678, 2 );
                {field:'curr_read_high',title:'LWBP',priority:2,width:100 ,  halign: 'center', align:'right',
                formatter: function(value,row,index){
                   return addCommas( row.curr_read_high );
                }},
                {field:'curr_read',title:'WBP',priority:3,width:100,  halign: 'center', align:'right', formatter: function(value,row,index){
                    return addCommas( row.curr_read );
                 }},
            ]]
        });

        function setFormConfirmation(data){
                $.messager.confirm('Confirm','Are you sure want to process confirmation ?',function(r){
                  if (r){
                    window.location.href = '/meter/confirmation/'+data.link;
                   }
                })
        }

        dg.datagrid('enableFilter');

        function addCommas(numberString) {
            numberString += '';
            var x = numberString.split('.'),
                x1 = x[0],
                x2 = x.length > 1 ? '.' + x[1] : '',
                rgxp = /(\d+)(\d{3})/;
          
            while (rgxp.test(x1)) {
              x1 = x1.replace(rgxp, '$1' + ',' + '$2');
            }
          
            return x1 + x2;
        }
})