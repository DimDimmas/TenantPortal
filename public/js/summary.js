$(document).ready(function () {
    var d = new Date();
    var year = d.getFullYear();
    var type = $("#type option:selected").val();

    $("#InputMonth").val(year);


    function chart(data = "") { 
        var chart = new CanvasJS.Chart("chartContainer", {
            theme: "light1", // "light2", "dark1", "dark2"
            backgroundColor: "#efeefd",
            animationEnabled: true, // change to true		
            title:{
                // text: "Basic Column Chart"
            },
            legend: {
                cursor:"pointer",
                verticalAlign: "top",
                fontColor: "dimGrey",
                fontSize: 10
             },
            data: [
            {
                // Change type to "bar", "area", "spline", "pie",etc.
                type: "column",
                legendText: "{label}",
                color: "#0b367f",
                dataPoints: data 
            }
            ]
        });
        chart.render();

       $(".canvasjs-chart-credit").hide();
    }

    function ajaxRequest(year = '', type = '') {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: '/meter/load_data_summary',
            data: {
                'year' : year,
                'type' : type,
                '_token' : CSRF_TOKEN
            },
            type: "POST",
            dataType: 'html',
            success:function(data) {
                var obj = jQuery.parseJSON(data);
                chart(obj.bar);
            }
        })
    }

    
    ajaxRequest(year, type);

    var $input2 = $('#InputMonth');
    $input2.datetimepicker({
        viewMode: "years", 
        format: 'YYYY'
    });

    $("#InputMonth").on('dp.change', function(e){
        var years = moment(e.date,"DD/MM/YYYY").format("YYYY");
        var type = $("#type option:selected").val();
        ajaxRequest(years, type);
    })

    $("#type").on('change', function(e){
        var year = $('#InputMonth').val();
        var type = $("#type option:selected").val();
        ajaxRequest(year, type);
    })

})


