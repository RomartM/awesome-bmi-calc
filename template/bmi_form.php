<?php

$ftSelect = [1,2,3,4,5,6,7];
$inSelect = [0,1,2,3,4,5,6,7,8,9,10,11,12];

?>
<div class="bmi-form-wrapper">
  <div class="bmi-form">
    <div class="input-form">
      <div class="input-container">
        <label for="weight_in_numbers">Weight</label>
        <input type="number" id="weight_in_numbers" min="1"/>
        <select id="weight_in_type" title="Weight Type">
          <option value="lbs">lbs</option>
          <option value="kg">kg</option>
        </select>
      </div>
      <div class="input-container">
        <div class="input-radio">
          <input type="radio" value="ft_in" name="height_in_type" id="in_ft_in"/>
          <label for="in_ft_in">Feet and Inches</label>
        </div>
        <div class="input-radio">
          <input type="radio" value="in_cm" name="height_in_type" id="in_cm_in"/>
          <label for="in_cm_in">Inches and Centimeter</label>
        </div>
      </div>
      <div id="ft_in">
        <div class="input-container">
          <label for="ftSelect">Feet</label>
          <select id="ftSelect">
            <?php for ($fts=0; $fts < count($ftSelect); $fts++) { ?>
              <option value="<?php echo $ftSelect[$fts]; ?>">
                <?php echo $ftSelect[$fts]; ?>'
              </option>
            <?php } ?>
          </select>
        </div>
        <div class="input-container">
          <label for="inSelect">Inches</label>
    			<select id="inSelect">
            <?php for ($ins=0; $ins < count($inSelect) ; $ins++) { ?>
              <option value="<?php echo $inSelect[$ins]; ?>">
                <?php echo $inSelect[$ins]; ?>"
              </option>
            <?php } ?>
    			</select>
        </div>
      </div>
      <div class="input-container" id="in_cm">
        <label for="in_cm_value">Height</label>
        <input type="number" id="in_cm_value" min="1">
  			<select id="in_cm_type" title="Height Type">
  				<option value="cm">cm</option>
  				<option value="in">in.</option>
  			</select>
      </div>
      <div class="input-container" id="calculate">
        <button id="submit">Calculate</button>
      </div>
    </div>
    <div class="output-form">
      <div class="bmi-value">
        <span></span>
        <div class="bmi-wrapper">
          <span class="bmi-label"></span>
        </div>
      </div>
      <div class="bmi-type" >
      You are <span></span>
      </div>
      <div class="input-container">
        <button id="reset">Try Again</button>
      </div>
      <div class="bmi-suggestion-wrapper">
        <div class="bmi-suggestion-label">
            You may try the following suggestions
        </div>
        <div class="bmi-suggestion-links">
          
        </div>
      </div>
    </div>
  </div>
</div>
