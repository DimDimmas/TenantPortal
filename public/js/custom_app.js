select2_select = function (
  element_id,
  url,
  element_id_chain = "",
  selected_element_value = "",
  selected_trigger_value = "",
  searchElement = "",
  clearOption = false,
  add_val = "",
  modal = ""
) {
  // modal = "#formProspect";
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
  if (searchElement != "") {
    var searcValue = Infinity;
  } else {
    var searcValue = false;
  }
  if (modal != "") {
    var searcModal = $(modal);
  } else {
    var searcModal = "";
  }
  $("#" + element_id)
    .select2({
      dropdownParent: searcModal,
      allowClear: true,
      minimumResultsForSearch: searcValue,
      width: "100%",
      ajax: {
        url: url,
        type: "GET",
        // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
        dataType: "json",
        data: function (params) {
          return {
            q: params.term, // search term
            trigger: $("#" + element_id_chain).select2("val"),
            page: params.page,
            addval: add_val,
            _token: CSRF_TOKEN,
          };
        },
        processResults: function (data) {
          // Tranforms the top-level key of the response object from 'items' to 'results'
          return {
            results: data.results,
          };
        },
        cache: true,
      },
      placeholder: "-",
      escapeMarkup: function (markup) {
        return markup;
      }, // let our custom formatter work
      templateResult: formatRepo,
      templateSelection: formatRepoSelection,
    })
    .on("select2:selecting", function (event) {
      // $('#'+element_id).find('option').remove();
      // if(clearOption == true) {
      //   $('#'+element_id).find('option').remove();
      // }
      if (element_id_chain && clearOption == true) {
        $("#" + element_id_chain).on("change", function () {
          $("#" + element_id)
            .val("")
            .trigger("change");
        });
      }
    });
  if (selected_element_value || selected_trigger_value) {
    // Fetch the preselected item, and add to the control
    var optSelect = $("#" + element_id);
    var option = "";
    if ($.isArray(selected_element_value)) {
      $.each(selected_element_value, function (index, value) {
        // var option = new Option(value.text, value.id, true, true);
        option +=
          "<option value='" +
          value.id +
          "' selected>" +
          value.text +
          "</option>";
      });
      optSelect.html(option).trigger("change");
    } else {
      $.ajax({
        type: "GET",
        url:
          url +
          "?id=" +
          selected_element_value +
          "&trigger=" +
          selected_trigger_value +
          "&addval=" +
          add_val,
      }).then(function (data) {
        //  var selectedValues = new Array();
        // selectedValues = ["tjendra.muljono@mmproperty.com", "ade.herawati@mmproperty.com", "ujang.ibrahim@mmproperty.coms"];
        // create the option and append to Select2
        var option =
          "<option value='" +
          data.results[0].id +
          "' selected>" +
          data.results[0].text +
          "</option>";
        // var option = new Option(data.results[0].text, data.results[0].id, true, true);
        optSelect.html(option);

        // optSelect.append(option).trigger('change');
        // $.each($("#ccResponse"), function(){
        //     $(this).val(selectedValues);
        // });

        // manually trigger the `select2:select` event
        optSelect.trigger({
          type: "select2:select",
          params: {
            data: data,
          },
        });
      });
    }
  }
};

// select2 by class zakki 05 July 2021
select2_select_class = function (
  element_class,
  url,
  element_class_chain = "",
  selected_element_value = "",
  selected_trigger_value = "",
  searchElement = "",
  clearOption = false,
  add_val = "",
  modal = ""
) {
  // modal = "#formProspect";
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
  if (searchElement != "") {
    var searcValue = Infinity;
  } else {
    var searcValue = false;
  }
  if (modal != "") {
    var searcModal = $(modal);
  } else {
    var searcModal = "";
  }
  $("." + element_class)
    .select2({
      dropdownParent: searcModal,
      allowClear: true,
      minimumResultsForSearch: searcValue,
      width: "100%",
      ajax: {
        url: url,
        type: "GET",
        // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
        dataType: "json",
        data: function (params) {
          return {
            q: params.term, // search term
            trigger: $("#" + element_class_chain).select2("val"),
            page: params.page,
            addval: add_val,
            _token: CSRF_TOKEN,
          };
        },
        processResults: function (data) {
          // Tranforms the top-level key of the response object from 'items' to 'results'
          return {
            results: data.results,
          };
        },
        cache: true,
      },
      placeholder: "-",
      escapeMarkup: function (markup) {
        return markup;
      }, // let our custom formatter work
      templateResult: formatRepo,
      templateSelection: formatRepoSelection,
    })
    .on("select2:selecting", function (event) {
      // $('#'+element_class).find('option').remove();
      // if(clearOption == true) {
      //   $('#'+element_class).find('option').remove();
      // }
      if (element_class_chain && clearOption == true) {
        $("." + element_class_chain).on("change", function () {
          $("." + element_class)
            .val("")
            .trigger("change");
        });
      }
    });
  if (selected_element_value || selected_trigger_value) {
    // Fetch the preselected item, and add to the control
    var optSelect = $("." + element_class);
    var option = "";
    if ($.isArray(selected_element_value)) {
      $.each(selected_element_value, function (index, value) {
        // var option = new Option(value.text, value.id, true, true);
        option +=
          "<option value='" +
          value.id +
          "' selected>" +
          value.text +
          "</option>";
      });
      optSelect.html(option).trigger("change");
    } else {
      $.ajax({
        type: "GET",
        url:
          url +
          "?id=" +
          selected_element_value +
          "&trigger=" +
          selected_trigger_value +
          "&addval=" +
          add_val,
      }).then(function (data) {
        //  var selectedValues = new Array();
        // selectedValues = ["tjendra.muljono@mmproperty.com", "ade.herawati@mmproperty.com", "ujang.ibrahim@mmproperty.coms"];
        // create the option and append to Select2
        var option =
          "<option value='" +
          data.results[0].id +
          "' selected>" +
          data.results[0].text +
          "</option>";
        // var option = new Option(data.results[0].text, data.results[0].id, true, true);
        optSelect.html(option);

        // optSelect.append(option).trigger('change');
        // $.each($("#ccResponse"), function(){
        //     $(this).val(selectedValues);
        // });

        // manually trigger the `select2:select` event
        optSelect.trigger({
          type: "select2:select",
          params: {
            data: data,
          },
        });
      });
    }
  }
};

function formatRepo(repo) {
  var markup =
    "<div class='select2-result-repository__title'>" + repo.text + "</div>";
  return markup;
}

function formatRepoSelection(repo) {
  return repo.text || repo.text;
}

select2 = function (
  element_id,
  uri,
  trigger_val = "",
  set_id = "",
  set_clear = {},
  trigger_val2 = ""
) {
  $("#" + element_id)
    .select2({
      allowClear: true,
      width: "100%",
      height: "20px",
      initSelection: function (element, callback) {
        var datax;
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

        if ($("#" + element_id).val() != "") {
          $.ajax({
            type: "POST",
            url: uri,
            data: {
              id: $("#" + element_id).val(),
              trigger: $("#" + trigger_val).val(),
              trigger2: $("#" + trigger_val2).val(),
              text: "",
              _token: CSRF_TOKEN,
            },
            success: function (returnValue, status) {
              callback(returnValue[0]);
            },
            error: function (e) {
              alertify.alert(e.responseText);
            },
          });
        } else {
          datax = { id: "", text: "" };
          callback(datax);
        }
      },
      ajax: {
        dataType: "json",
        url: uri,
        type: "post",
        data: function (term, page) {
          return {
            text: term,
            trigger: $("#" + trigger_val).val(),
            trigger2: $("#" + trigger_val2).val(),
            _token: $('meta[name="csrf-token"]').attr("content"),
            id: "",
          };
        },
        results: function (data, page) {
          return { results: data };
        },
      },
    })
    .on("select2-selecting", function (e) {
      if (set_id != "") {
        $("#" + set_id).val(e.val);
      }
    })
    .on("change", function (e) {
      if (set_clear[0] != "") {
        $("#" + set_clear[0]).val("");
      }
      if (set_clear[1] != "") {
        $("#" + set_clear[1]).select2("val", "");
      }
    });
};

ScrollTo = function (id) {
  $("html, body").animate(
    {
      scrollTop: eval($("#" + id).offset().top - 70),
    },
    500
  );
};
