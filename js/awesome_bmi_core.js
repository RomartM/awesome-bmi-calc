/**
 * @file
 * BMI Customization Dashboard Dependency Class
 *
 * This class is responsible for storing temporary data object builds, generating
 * HTML based datas, and complete manipulation of core dashboard live data's
 */
! function(e) {
  "use strict";
  var $ = jQuery;

  class ABCore {

    constructor(manifest, selector = ".bmi-selected-data", data_selector = ".bmi-checkbox") {
      this.manifest = manifest;
      this.data_core = this.manifest;
      this.parent_prefix = "data";
      this.selector = selector;
      this.data_selector = data_selector;
      this.data_dictionary = [];
      this.deleted_items = [];
      this.init();
    }

    init() {
      for (var i = 0; i < $(this.data_selector).length; i++) {
        this.data_dictionary.push(`${$(this.data_selector+">input")[i].value}^${$(this.data_selector+">span")[i].innerHTML}`)
      }
      this.data_dictionary = [...new Set(this.data_dictionary)]
    }

    loadData(data) {
      for (var i = 0; i < data.length; i++) {
        for (var n = 0; n < this.data_core.length; n++) {
          if (Number(data[i].type) === Number(this.data_core[n].id)) {
            if ((this.data_core[n])["data"] === undefined) {
              (this.data_core[n])["data"] = [];
              --n;
            } else {
              if (((this.data_core[n])["data"])[data[i].postype] === undefined) {
                ((this.data_core[n])["data"])[data[i].postype] = []
                --n;
              } else {
                ((this.data_core[n])["data"])[data[i].postype].push(data[i].postype_id);
              }
            }
          }
        }
      }
      this.build();
    }

    getItemName(id) {
      if (this.data_dictionary.length >= 1) {
        for (var i = 0; i < this.data_dictionary.length; i++) {
          if (Number(this.data_dictionary[i].split('^')[0]) === Number(id)) {
            return this.data_dictionary[i].split('^')[1];
          }
        }
      } else {
        // console.log("Dictionary Empty");
      }
    }

    process(data) {
      // Loop manifest replica
      for (var i = 0; i < this.data_core.length; i++) {
        // Seek for data parameter matched id
        if (Number(data.state_id) === Number(this.data_core[i].id)) {
          // Dynamic variable verification existence
          if (this.data_core[i][this.parent_prefix] === undefined) {
            // Initialize parent dynamic variable when undefined
            this.data_core[i][this.parent_prefix] = [];
            // Initialize child variable object
            this.data_core[i].data[data.postype] = [];
          } else {
            // Verify child dynamic varible does exist
            if (this.data_core[i].data[data.postype] === undefined) {
              // Initialize child variable object
              this.data_core[i].data[data.postype] = [];
            }
          }
          // Loop data param postype ID's
          for (var n = 0; n < data.postype_id_list.length; n++) {
            // Push post id's into dynamic postype variable object
            this.data_core[i].data[data.postype].push(data.postype_id_list[n]);
          }
          // Filter duplicate ID's before storing to replica manifest
          this.data_core[i].data[data.postype] = [...new Set(this.data_core[i].data[data.postype])];
        }
      }
    }

    initial_build() {
      var temp_store = "";
      for (var i = 0; i < this.data_core.length; i++) {
        if (this.data_core[i][this.parent_prefix] !== undefined) {
          var child_object = Object.keys(this.data_core[i].data);
          // console.log(child_object);
          if (child_object.length !== 0) {
            for (var n = 0; n < child_object.length; n++) {
              if (this.data_core[i].data[child_object[n]] !== undefined && this.data_core[i].data[child_object[n]].length >= 1) {
                temp_store += `<div class="bmi-data-list-wrapper data-id-${this.data_core[i].id}-${child_object[n]}">
                <div class="bmi-data-list-title">
                  <span class="bmi-data-name">${this.data_core[i].name.replace("_", " ")}</span>
                  <span class="bmi-data-child">${child_object[n].replace("_", " ")}</span>
                </div>
                <ul class=\"bmi-data-list sortable bmi-data-${this.data_core[i].name.replace(" ", "_").toLowerCase()}-${child_object[n]}\" data-id="${this.data_core[i].id}" data-child="${child_object[n]}"></ul>
                </div>`;
              }
            }
          }
        }
      }
      $(this.selector).html(temp_store);
      $(".sortable").sortable();
      $(".sortable").disableSelection();
    }

    remove_item(data_object) {
      var pre_format_data = data_object.parent.split(".");
      function arrayRemove(arr, value) {
        return arr.filter(function(ele) {
          return ele != value;
        });
      }
      for (var i = 0; i < this.data_core.length; i++) {
        if (String(pre_format_data[0]) === String(this.data_core[i].name)) {
          if (this.data_core[i]["data"] !== undefined) {
            this.deleted_items.push(`${data_object.refid}${data_object.id}${pre_format_data[1]}`);
            this.data_core[i].data[pre_format_data[1]] = arrayRemove(this.data_core[i].data[pre_format_data[1]], data_object.refid);
          }
        }
      }
      this.build();
    }

    get_item_length(data_object) {
      var pre_format_data = data_object.parent.split(".");
      for (var i = 0; i < this.data_core.length; i++) {
        if (String(pre_format_data[0]) === String(this.data_core[i].name)) {
          if (this.data_core[i]["data"] !== undefined) {
            return this.data_core[i].data[pre_format_data[1]].length;
          }
        }
      }
    }

    build_list() {
      for (var i = 0; i < this.data_core.length; i++) {
        if (this.data_core[i][this.parent_prefix] !== undefined) {
          var child_object = Object.keys(this.data_core[i].data);
          if (child_object.length !== 0) {
            for (var n = 0; n < child_object.length; n++) {
              if (this.data_core[i].data[child_object[n]] !== undefined) {
                var r = "";
                for (var j = 0; j < this.data_core[i].data[child_object[n]].length; j++) {
                  r += `<li class="ui-state-default" data-id=\"${this.data_core[i].data[child_object[n]][j]}\">
  									<div class="item-label">${this.getItemName(this.data_core[i].data[child_object[n]][j])}</div>
                    <div class="item-action-wrapper">
                    <button class="action-item-remove" data-id="${this.data_core[i].id}" data-parent="${this.data_core[i].name}.${child_object[n]}"  data-refid="${this.data_core[i].data[child_object[n]][j]}">Remove</button>
                    </div>
                    </li>`;
                }
                $(`.bmi-data-list.bmi-data-${this.data_core[i].name.replace(" ", "_").toLowerCase()}-${child_object[n]}`).html(r);
              }
            }
          }
        }
      }
    }

    build() {
      this.initial_build();
      this.build_list();
    }
  }
  "undefined" != typeof exports ? exports.ABCore = ABCore : e.ABCore = ABCore
}("undefined" != typeof global ? global : this);
