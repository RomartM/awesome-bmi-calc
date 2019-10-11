jQuery(document).ready(function($) {

  // Common Variable Declaration
  var ft_in = $("#ft_in");
  var in_cm = $("#in_cm");
  var height_in_type = $('input[name="height_in_type"]');
  var calculate = $("#submit");
  var output_form = $(".output-form");

  // Default Preset
  $("#in_ft_in").attr('checked', 'checked');
  $("#ft_in").show();

  // Listen any input changes
  $('#ft_in, \
     #in_cm, \
     input[name="height_in_type"], \
     #weight_in_numbers, \
     #weight_in_type, \
     #ftSelect, \
     #inSelect, \
     #in_cm_value, \
     #in_cm_type').change(() => {
    if (output_form.hasClass('abc-results')) {
      output_form.removeClass('abc-results');
    }
  })

  // Reset Form
  $("#reset").click(() => {
    $(".output-form").fadeOut("slow", () => {
      $(".input-form").fadeIn("slow");
    });
  })

  // Calculate Inputed Datas
  calculate.click(() => {
    // Get Weight
    var weight_in_numbers = $("#weight_in_numbers").val();
    var weight_in_type = $("#weight_in_type").val();

    // Weight data validation
    if (!weight_in_numbers || (weight_in_numbers < 20 || weight_in_numbers > 600 || weight_in_numbers < 1)) {
      return alert('Weight value is missing or invalid.');
    }

    // Check if weight by type
    if (weight_in_type === "kg") {
      weight_in_numbers *= 2.20462262;
    }

    // Get checked value
    var selected = $('input[name="height_in_type"]:checked').val();

    // Get Height
    var height_obj = getHeightSelected(selected);

    // Show errors if exist
    if (height_obj.error) {
      return alert(height_obj.error);
    }
    // Get BMI
    var result = getBMI(height_obj.result, weight_in_numbers);
    $(".input-form").fadeOut("fast", () => {
      $(".output-form").fadeIn("fast", () => {
        // Set BMI Value
        $(".bmi-value>span").text(result.bmi);
        // Set BMI Type
        $(".bmi-type>span").attr('data-content', `${result.type}`);
        // Set background color
        $(".bmi-value").css('background', `linear-gradient(-10deg, ${result.color})`);
        $(".bmi-suggestion-links").html(build_suggested_links(result.id));
        // Show Results
        output_form.addClass('abc-results');
      });
    });
  });

  function build_suggested_links(state_id) {
    /*
    HTML Prestyled Anchors for suggested manifest object
    @param state_id {Number} - bmi state ID
    @return {String} - Concatenated HMTL codes
    */

    var initial_data = "";
    var links = JSON.parse(abc_manifest.suggested_links);
    for (var i = 0; i < links.length; i++) {
      if (Number(state_id) === Number(links[i].type)) {
        initial_data += `<div class="bmi-suggested-link-wrapper">
					<a href="${links[i].permalink.link}" target="_blank" title="${links[i].permalink.title}">
          <div class="bmi-link-thumbnail">
						${links[i].permalink.thumbnail}
					</div>
          <div class="bmi-link-label">
          ${links[i].permalink.title}
          </div>
          </a>
				   </div>`;
      }
    }
    return initial_data;
  }

  function getHeightSelected(selected) {
    /*
    Get the selected height method and then use the method
    @param selected {String} - Type of height method
    @return {Object} - Converted height values
    */
    var error_log = "";
    var result = 0;
    // Select Height by Method
    if (selected === "ft_in") {
      // Get Feet and Inches in Height
      var feet_val = $("#ftSelect").val();
      var inches_val = $("#inSelect").val();
      // Calculate by Feet and Inch
      result = (feet_val * 12) + (1 * inches_val);
    } else if (selected === "in_cm") {
      // Get by Centimeter or Inches  in Height
      var height_val = $("#in_cm_value").val();
      var height_type = $("#in_cm_type").val();
      // Data input validation
      if (height_val > 500 || height_val === null || height_val === undefined || height_val < 1) {
        error_log += 'Height value is missing or invalid.' + "\n";
      }

      // Calculate
      if (height_type === "cm") {
        result = height_val * 0.393700787;
      } else {
        result = height_val;
      }
    }
    // Return object
    return {
      'result': result,
      'error': error_log
    }
  }

  function getBMI(height_value, weight_value) {
    /*
    Core Calculation of BMI
    @param height_value {Float} - Converted Height
    @param weight_value {Float} - Converted Weight
    @return {Object} - Raw Calculated Data
    */
    var bmi = Math.round((weight_value / (height_value * height_value)) * 703 * 10) / 10;
    var payload = JSON.parse(abc_manifest.state_manifest);
    return dynamicBMI(bmi, payload);
  }

  function dynamicBMI(bmi, payload) {
    /*
    JSON Feed Algorithm for core calculation
    @param bmi {Float} - user bmi initial
    @param payload {Object} - JSON type formula
    @return {Object} - Raw Calculated Data
    */

    // Generates Object Link Data
    var picker = (arr, index) => {
      var color = arr[index].color.split(':');
      return {
        "color": `${color[0]}, ${color[1]}`,
        "type": arr[index].name,
        "bmi": bmi,
        "id": arr[index].id
      }
    }
    // Loop payload
    for (var i = 0; i < payload.length; i++) {
      var chunk = payload[i].range.split(':')
      if (chunk[0] == "inf" && chunk[1] !== "inf") {
        if (bmi < Number(chunk[1])) {
          return picker(payload, i);
        }
      } else if (chunk[0] !== "inf" && chunk[1] == "inf") {
        if (bmi > Number(chunk[0])) {
          return picker(payload, i);
        }
      } else {
        if (bmi >= chunk[0] && bmi < chunk[1]) {
          return picker(payload, i);
        }
      }
    }
  }

  // Height method selection listener
  height_in_type.click(function() {
    switch (this.value) {
      case "ft_in":
        ft_in.show();
        in_cm.hide();
        break;
      case "in_cm":
        in_cm.show();
        ft_in.hide();
        break;
    }
  });

});
