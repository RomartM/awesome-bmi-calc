<?php

global $wpdb;

$table_standards = $wpdb->prefix. BMI_DB_PREFIX . 'standards';

$results = $wpdb->get_results("SELECT * FROM $table_standards");

 ?>

 <div class="bmi-selected-state md-select">
  <label for="bmi_states"><button type="button">Select State</button></label>
  <ul role="listbox" id="bmi_states">
    <?php foreach ($results as $items) {
      ?>
      <li role="option" id="bmi_state_items state_<?php echo $items->id; ?>" data-range="<?php echo $items->range; ?>" data-id="<?php echo $items->id; ?>" data-type="<?php echo $items->name; ?>"><?php echo ucwords($items->name); ?></li>
      <?php
    } ?>
  </ul>
</div>
