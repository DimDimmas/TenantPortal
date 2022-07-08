$(document).ready(function () {
  // call select2
  $(".select2").select2();
  // call select2

  $("#statusOvertimeBy").on("change", function (e) {
    dataTable = $("#tableOvertime").DataTable();
    var status = $(this).val();
    $("#statusBy").val(status);
    console.log(status);
    //dataTable.column(6).search('\\s' + status + '\\s', true, false, true).draw();
    dataTable.column(10).search(status).draw();
  });

  // datepicker show date and day
  $('[data-toggle="datepicker"]').change(function () {
    var weekday = new Array(7);
    weekday[6] = "Sunday";
    weekday[0] = "Monday";
    weekday[1] = "Tuesday";
    weekday[2] = "Wednesday";
    weekday[3] = "Thursday";
    weekday[4] = "Friday";
    weekday[5] = "Saturday";
    var a = $(this).datepicker("getDate");
    var dayOfWeek = weekday[a.getUTCDay()];
    $("#day").val(dayOfWeek);
    console.log(a);
    console.log(a.getDate());
    $("#duration").val("");
    $("#end_time").empty();
    $.ajax({
      url: "/overtime/get-time/" + convert(a),
      method: "GET",
      dataType: "html",
      success: function (result) {
        $("#start_time").html(result);
      },
    });
  });
  // datepicker show date and day

  function convert(str) {
    var date = new Date(str),
      mnth = ("0" + (date.getMonth() + 1)).slice(-2),
      day = ("0" + date.getDate()).slice(-2);
    return [date.getFullYear(), mnth, day].join("-");
  }
  

  // get start time overtime
  $("#start_time").click(function () {
    // $('#duration').val("");
    var start = $(this).val();
    start = (start == 'null' ? '17' : start);
    console.log(start);
    $.ajax({
      url: "/overtime/get-start-time/" + start,
      method: "GET",
      dataType: "html",
      success: function (result) {
        $("#end_time").html(result);
      },
    });
  });
  // get start time overtime

  // button add user
  var max_fields = 10; //maximum input boxes allowed
  var wrapper = $(".wrap_user"); //Fields wrapper
  var add_button = $(".add_field_button"); //Add button ID

  var x = 1; //initlal text box count
  $(add_button).click(function (e) {
    //on add input button click
    e.preventDefault();
    if (x < max_fields) {
      //max input box allowed
      x++; //text box increment
      $(wrapper).append(
        '<div class="col-sm-12 mb-3" style="padding-left: 0"><input type="text" class="form-control new-form mr-3" name="user[]" id="user" oninvalid="this.setCustomValidity("User belum diisi. Jika hanya 1 user, hapus kolom ini.")" onchange="this.setCustomValidity("")" required><a href="#" class="remove_field" style="font-size: 25px;"><i class="fa fa-times-circle" aria-hidden="true" style="color: #d9534f"></i></a><div>'
      ); //add input box
    }
  });

  $(wrapper).on("click", ".remove_field", function (e) {
    //user click on remove text
    e.preventDefault();
    $(this).parent("div").remove();
    x--;
  });
  // button add user

  $("#tableOvertime thead .dt-search").each(function () {
    var title = $(this).text();
    $(this).html('<input type="text" placeholder="' + title + '" />');
  });

  // DataTable
  var table = $("#tableOvertime").DataTable({
    ordering: false,
    initComplete: function () {
      this.api()
        .columns()
        .every(function () {
          var that = this;

          $("input", this.header()).on("keyup change clear", function () {
            if (that.search() !== this.value) {
              that.search(this.value).draw();
            }
          });
        });
    },
  });

  $("#tableRequestTicket thead .dt-search").each(function () {
    var title = $(this).text();
    $(this).html('<input type="text" placeholder="' + title + '" />');
  });

  // DataTable
  var table = $("#tableRequestTicket").DataTable({
    ordering: false,
    initComplete: function () {
      this.api()
        .columns()
        .every(function () {
          var that = this;

          $("input", this.header()).on("keyup change clear", function () {
            if (that.search() !== this.value) {
              that.search(this.value).draw();
            }
          });
        });
    },
  });
});

// get duration end time
$(document).on("click", "#end_time", function () {
  var a = $("#start_time option:selected").val();
  var b = $("#end_time option:selected").val();

  // var c = ;
  $.ajax({
    type: "get",
    url: "/overtime/get-duration/",
    data: {
      start: a,
      end: b,
    },
    success: function (data) {
      console.log(data);
      $("#duration").val(data); // for debugging
    },
    error: function (response) {
      console.error(response); // for debugging
    },
  });
  // $('#duration').val(b);
  console.log(a);
  console.log(b);
});
// get duration end time

// call select2
// $(document).ready(function () {
var uri0 = "/corrective/request-ticket/get-category-id/";
//     var uri1 = '/corrective/ajax/get_type_ticket';
//     var uri1 = '/corrective/ajax/get_cate_ticket';
jQuery("#formId").on("select2:select", function () {
  // console.log('test');
  select2_select("cateId", uri0, "formId", "", "", "", true);
  allowClear: true;
});
// call select2
