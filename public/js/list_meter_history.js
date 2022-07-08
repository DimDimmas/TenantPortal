jQuery(document).ready(function ($) {

       // $('#dateFrom').datebox({
    //     onSelect: function(date){
    //         console.log(FormatDate(date));
    //         opt.datagrid('reload');
    //     }
    // });

        var df = new Date();
        df.setDate(1);

        var dt = new Date();

        $('#dateFrom, #dateTo').datebox(
            {
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    
                    // date format dd/mm/yyyy
                    var r = (d < 10 ? ('0' + d) : d) + '/' + 
                            (m < 10 ? ('0' + m) : m) + '/' + 
                            y;
                    return r;
                },

                parser: function(s) {
                    if (!s) {
                        return new Date();
                    }
                    // date format dd/mm/yyyy
                    var ss = (s.split('/'));
                    var d = parseInt(ss[0], 10);
                    var m = parseInt(ss[1], 10);
                    var y = parseInt(ss[2], 10);
                    
                    if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
                        return new Date(y, m - 1, d);
                    } else {
                        return new Date();
                    }
                }
            }
        );

        function FormatDate(date, getD = 0) {
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = (getD>0 ? getD : date.getDate() );
            return (m<10?('0'+m):m)+'/'+(d<10?('0'+d):d)+'/'+y;
        }


        var oldRowIndex;
        var dg;

        function updateDataGrid(opt, df, dt) {
            dg = opt.datagrid({
                url: '/meter/grid-history',
                queryParams: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    datefrom : df,
                    dateto : dt,
                },
                pagination: true,
                clientPaging: false,
                remoteFilter: true,
                rownumbers: true,
                fitColumns: true,
                onClickRow: function (rowIndex) {
                    var selectRow = opt.datagrid('getSelected');
                    viewForm(selectRow);
                
                    oldRowIndex = opt.datagrid('getRowIndex', selectRow);

                    opt.datagrid('unselectRow', rowIndex);

                },
                columns: [[
                    {field:'action',title:'Action',width:80,align:'center',  rowspan:2,
                        formatter:function(value,row,index){
                            // var d = '<button id="btn" style="border:0" class="easyui-linkbutton icon-file-t xt-alt">View</button>';
                            // return d;
                            var d = formatDetail(row);
                            return d;
                        }
                    },
                    {field:'entity_name',title:'Entity', halign: 'center',  width:120,  rowspan:2},
                    {field:'project_name',title:'Project', halign: 'center',  width:120, rowspan:2},
                    {field:'panel',title:'Panel', halign: 'center',   width:100, priority:1, rowspan:2 },
                    {field:'meter_id',title:'Meter ID',  width:80, halign: 'center', priority:4, rowspan:2},
                    {field:'meter_type',title:'Type', halign: 'center', width:100, rowspan:2, align: 'center'},
                    {field:'date_read', title:'Reading Date', width:100, rowspan:2, align: 'center'},
                    {title:'Last Read', colspan:2},
                    {title:'Current Read', colspan:2},
                    {field:'usage', title:'Usage', rowspan:2, align: 'right',
                        formatter: function(value,row,index){
                            return addCommas( row.usage );
                        }
                    },
                    {field:'usage_high', title:'Usage_High', rowspan:2, align: 'right', 
                        formatter: function(value,row,index){
                        return addCommas( row.usage_high );
                        }
                    },
                    {field:'trx_amt',title:'Amount',  width:80, halign: 'center', rowspan:2, align: 'right',
                    formatter: function(value,row,index){
                    return addCommas( row.trx_amt );
                    }},

                    ], //$.number( 1234.5678, 2 );
                    [
                    {field:'last_read',title:'LWBP',width:100 ,  halign: 'center', align:'right',
                    formatter: function(value,row,index){
                    return addCommas( row.last_read );
                    }},
                    {field:'last_read_high',title:'WBP',width:100,  halign: 'center', align:'right', 
                    formatter: function(value,row,index){
                        return addCommas( row.last_read_high );
                    }},

                    {field:'curr_read',title:'LWBP',width:100 ,  halign: 'center', align:'right',
                    formatter: function(value,row,index){
                    return addCommas( row.curr_read );
                    }},
                    {field:'curr_read_high',title:'WBP',width:100,  halign: 'center', align:'right', 
                    formatter: function(value,row,index){
                        return addCommas( row.curr_read_high );
                    }},
                    ]
                ]
            });
        }

        function setFormConfirmation(data){
            $.messager.confirm('Confirm','Are you sure want to process confirmation Panel : ?',function(r){
                if (r){
                window.location.href = '/meter/confirmation/'+data.link;
                }
            })
        }

        function formatDetail(row){
            return '<a class="easyui-linkbutton viewForm" style="text-decoration: underline;" href="javascript:void(0)">View</a>';
        }

    
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

        var opts = $('#dateFrom').datebox('options');
        // $('#dateFrom').datebox('setValue', opts.formatter(df));
        // $('#dateTo').datebox('setValue',  opts.formatter(dt));

        $('#dateFrom').datebox({
            setValue: opts.formatter(df),
            onSelect: function(date) {
                var dateTo = $('#dateTo').datebox('getValue');
                doSearch( opts.formatter(date), dateTo);
            }
        })

        $('#dateTo').datebox({
            setValue: opts.formatter(dt),
            onSelect: function(date) {
                var dateFrom = $('#dateFrom').datebox('getValue');
                doSearch(dateFrom,  opts.formatter(date));
            }
        })

        $('#dateFrom').datebox('setValue',opts.formatter(df));
        $('#dateTo').datebox('setValue',opts.formatter(dt));


        var opt = $("#datahistory");
        var df = $('#dateFrom').datebox('getValue');
        var dt = $('#dateTo').datebox('getValue');
        updateDataGrid(opt, df, dt);

        function viewForm(row) { 
            var uri = '/img/bms/photo/';
            $('#tenant').val(row.debtor_name);
            $('#panel').val(row.panel);
            $('#meter_id').val(row.meter_id);
            $('#type').val( ( row.type = 'E' ? 'Electricity' : 'Water' ) );
            $('#read_date').val(moment(row.read_date).format('DD MMMM YYYY'));
            $('#last_wbp').val($.number( row.last_read_high, 2 ));
            $('#last_lwbp').val($.number( row.last_read, 2 ));
            $('#curr_wbp').val($.number( row.curr_read_high, 2 ));
            $('#curr_lwbp').val($.number( row.curr_read, 2 ) );
            if(row.signature != '') {
                $('#signature_tenant').html('<img src="'+row.signature+'" class="img-fluid img-thumbnail" style="width:300px; height:150px">');       
            } 
            if(row.tenant_name != '' || row.tenant_name != '-' || row.tenant_name != null) {
                $('#tenant_name').html('('+row.tenant_name+')');       
            }  else {
                $('#tenant_name').html('(.............................)');
            }
            var img = '';

            if(row.attachment != null) {
                console.log(row.attachment);
                if(row.attachment.includes(';;') == true ){
                    var ray_attach = row.attachment.split(';;');
                    $.each(ray_attach, function (i,val) {
                        img += '<div class="img"><img src="'+uri+val+'" style="max-width:80%; max-height:auto" class="img-thumbnail zoomimage"></div>';
                    })
                    $('#image').html(img);
                } else {
                    img = '<div class="img"><img src="'+uri+row.attachment+'" style="max-width:80%; max-height:auto" class="img-thumbnail zoomimage"></div>';
                    $('#image').html(img);
                }
            } else {
                $('#image').html("");
            }
            
            $('#engineering_name').html(row.capture_by);
            $('#view').modal('show');
            $('.modal').css('overflow-y', 'auto');
        }
        

        $(this).on('click', '.zoomimage', function() {
            var img = '<img src="'+ $(this).attr("src") +'"  style="width:auto; max-height:500px">';
            $('.modal').css('overflow-y', 'auto');
            $('#image_capture').html(img);
            $('#show_images').modal('show');
        })


        dg.datagrid('enableFilter');
        dg.datagrid('removeFilterRule','action');

        // $('#dateFrom').datebox({
        //     onSelect: function(date){
        //         // $('#dateFrom').datebox('setValue', FormatDate(date) );
        //         // alert(date.getFullYear()+":"+(date.getMonth()+1)+":"+date.getDate());
        //         doSearch();
        //     }
        // });
        function doSearch(date_from, date_to){
            var params = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                datefrom : date_from,
                dateto : date_to,
            }
            opt.datagrid('load', params); 
        }
    })

