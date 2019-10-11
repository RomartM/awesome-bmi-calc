jQuery(document).ready(function($) {

  function getCustomAttribute(context, attr_name) {
    return context.currentTarget.attributes[attr_name].nodeValue;
  }

  $("#material-tabs>a").click((event) => {
    var yellow_bar = $(".yellow-bar");
    yellow_bar.css('left', event.currentTarget.offsetLeft);
    yellow_bar.css('width', event.currentTarget.offsetWidth);
  });

  $('#material-tabs').each(function() {

    var $active, $content, $links = $(this).find('a');

    $active = $($links[0]);
    $active.addClass('active');

    $content = $($active[0].hash);

    $links.not($active).each(function() {
      $(this.hash).hide();
    });

    $(this).on('click', 'a', function(e) {

      $active.removeClass('active');
      $content.hide();

      $active = $(this);
      $content = $(this.hash);

      $active.addClass('active');
      $content.show();

      e.preventDefault();
    });
  });

  $('#post_types').change((event) => {
    var data = {
      'action': 'get_posts',
      'post_type': event.currentTarget.value
    };
    // Contact AJAX for requested data
    jQuery.post(abc_manifest.ajax_url, data, function(response) {
      var data = JSON.parse(response).data;
      var html = "";

      for (var i = 0; i < data.length; i++) {
        html = html + `<li><input type="checkbox" value="${data[i].post_name}"
					id="pd_${data[i].ID}">${data[i].post_title}</input></li>`;
      }
      $("#posts_data>li").remove();
      $("#posts_data").append(html);
    });
  });

  $('ul.mini-tabs li').click(function() {
    var tab_id = $(this).attr('data-tab');

    $('ul.mini-tabs li').removeClass('current');
    $('.mini-tab-content').removeClass('current');

    $(this).addClass('current');
    $("#" + tab_id).addClass('current');
  });

  $('.bmi-selected-state.md-select').on('click', function() {
    $(this).toggleClass('active');
  });

  $('.bmi-selected-state.md-select ul li').on('click', function(e) {
    var v = $(this).text();
    $('.bmi-selected-state.md-select ul li').not($(this)).removeClass('active');
    $(this).addClass('active');
    $('.bmi-selected-state.md-select label button').text(v);
    $('.selected-type').text(v);
    $(".bmi-checkbox>input").removeAttr("checked");
    $("div.set>button").removeAttr("disabled");
    $("div.set>button").removeAttr("title");
    $('.bmi-add-item').attr('data-selected-type', getCustomAttribute(e, "data-type"));
    $('.bmi-add-item').attr('data-selected-type-id', getCustomAttribute(e, "data-id"));
    $(`.bmi-data-list-wrapper.bmi-data-list-wrapper-active`).removeClass("bmi-data-list-wrapper-active");
    $(`.bmi-data-list-wrapper.data-id-${getCustomAttribute(e, "data-id")}-${getCustomAttribute(e, "data-type")}`).addClass("bmi-data-list-wrapper-active");
  });

  $("#customizer-data-save").on('click', function(e) {
    var parent_data = [];
    var item_data = []
    for (var i = 0; i < $(".bmi-data-list").length; i++) {
      for (var n = 0; n < $(".bmi-data-list")[i].children.length; n++) {
        item_data.push($(".bmi-data-list")[i].children[n].attributes["data-id"].nodeValue);
      }
      parent_data.push({
        "postype": $(".bmi-data-list")[i].attributes["data-child"].nodeValue,
        "state_type_id": $(".bmi-data-list")[i].attributes["data-id"].nodeValue,
        "id_list": item_data,
      });
      item_data = [];
    }
    var data = {
      'action': 'save_customizer',
      'data': parent_data,
      'deleted_items': core.deleted_items
    };
    // Contact AJAX for submitting query
    jQuery.post(abc_manifest.ajax_url, data, function(response) {
      var status = JSON.parse(response).status;
      switch (status) {
        case 200:
          snackbar.make("message", [ "Save Successfully", null, "bottom", "center" ], 3000);
          break;
        default:
          snackbar.make("message", [ "Unknow Error", null, "bottom", "center" ], 3000);
      }
    });
  });

  $(".bmi-add-item").click((e) => {
    var section = getCustomAttribute(e, "data-section");
    checked_list = $(`.bmi-checkbox>input.bmi-checkbox-${section}`);
    var initial_cast_data = [];
    for (var i = 0; i < checked_list.length; i++) {
      if (checked_list[i].checked) {
        initial_cast_data.push(checked_list[i].value)
      }
    }
    var manifest = {
      'state_type': getCustomAttribute(e, "data-selected-type"),
      'state_id': getCustomAttribute(e, "data-selected-type-id"),
      'postype': section,
      'postype_id_list': [...new Set(initial_cast_data)]
    }
    core.process(manifest);
    core.build();
    $(".bmi-data-list-wrapper.bmi-data-list-wrapper-active").removeClass("bmi-data-list-wrapper-active");
    $(`.bmi-data-list-wrapper.data-id-${getCustomAttribute(e, "data-selected-type-id")}-${section}`).addClass("bmi-data-list-wrapper-active");
    ActiveElementPosition();
  });

  $(document).on("click", "button.action-item-remove", function(e) {
    var generate = {
      "id": e.currentTarget.getAttribute("data-id"),
      "parent": e.currentTarget.getAttribute("data-parent"),
      "refid": e.currentTarget.getAttribute("data-refid")
    }
    var pre_format = e.currentTarget.getAttribute("data-parent").split(".");
    core.remove_item(generate);
    if (core.get_item_length(generate) === 0) {
      $(`.bmi-data-list-wrapper.data-id-${e.currentTarget.getAttribute("data-id")}-${pre_format[1]}`).remove();
    }
    $(".bmi-data-list-wrapper.bmi-data-list-wrapper-active").removeClass("bmi-data-list-wrapper-active");
    $(`.bmi-data-list-wrapper.data-id-${getCustomAttribute(e, "data-id")}-${pre_format[1]}`).addClass("bmi-data-list-wrapper-active");
    ActiveElementPosition();
  });

  $(".set > button").on("click", function() {
    if ($(this).hasClass("active")) {
      $(this).removeClass("active");
      $(this)
        .siblings(".content")
        .slideUp(200);
      $(".set > button i")
        .removeClass("fa-minus")
        .addClass("fa-plus");
    } else {
      $(".set > button i")
        .removeClass("fa-minus")
        .addClass("fa-plus");
      $(this)
        .find("i")
        .removeClass("fa-plus")
        .addClass("fa-minus");
      $(".set > button").removeClass("active");
      $(this).addClass("active");
      $(".content").slideUp(200);
      $(this)
        .siblings(".content")
        .slideDown(200);
    }
  });

  function ActiveElementPosition() {
    try {
      $(".bmi-seleted-data-wrapper").scrollTop($(".bmi-data-list-wrapper-active").position().top);
    } catch (e) {
        console.log('Element Removed');
    }
  }

  function search_list(input, ul) {
    $(input).on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $(ul).filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  }

  // Active Search Initialization
  search_list('#mini-tab-input-page-search-all', 'div#mini-tab-all-page>div>ul li');
  search_list('#mini-tab-input-page-search-recent', 'div#mini-tab-recent-page>div>ul li');
  search_list('#mini-tab-input-posts-search-all', 'div#mini-tab-all-posts>div>ul li');
  search_list('#mini-tab-input-posts-search-all', 'div#mini-tab-all-posts>div>ul li');
  search_list('#mini-tab-input-cat-search-all', 'div#mini-tab-all-cat>div>ul li');

  function init() {
    var active = $("#material-tabs>a.active");
    var yellow_bar = $(".yellow-bar");
    var manifest = JSON.parse(abc_manifest.state_manifest);
    yellow_bar.css('left', active[0].offsetLeft);
    yellow_bar.css('width', active[0].offsetWidth);
    $("div.set>button").prop("title", "Please select state first!");
    $("div.set>button").prop("disabled", true);
    return new ABCore(manifest, selector = ".bmi-selected-datas");
  }

  function loadData() {
    var r = "";
    try {
      r = JSON.parse(abc_manifest.customizer_data);
      if (typeof(r) === "object") {
        core.loadData(r);
      } else {
        console.log("Insufficient data supplied");
      }
    } catch (e) {
      console.log(e);
    }
  }
  // Initialization
  var core = init();
  // SnackBar Initialazation
  const snackbar = new SnackBar;
  // Load Customizer Data
  loadData();
});
