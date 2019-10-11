<div class="bmi-admin-dashboard-container">
  <div class="bmi-admin-header">
    <div class="bmi-logo">
      <img src="<?php echo plugins_url($app->icon, __FILE__); ?>" alt="Awesome BMI Logo" />
    </div>
    <div class="bmi-info">
      <h1><?php echo $app->title; ?></h1>
      <div class="bmi-description">
        <?php echo $app->description; ?>
      </div>
    </div>
  </div>
  <div class="bmi container">
  		<div class="bmi-container-border">
        <header>
    				<div id="material-tabs">
              <?php foreach($tabs_object as $data): ?>
                <a
                    id="<?php echo $data['id']; ?>-tab"
                    href="#<?php echo $data['id']; ?>"
                      <?php echo ($data['isActive']) ? 'class="active"' : '' ; ?>
                  >
                    <?php  echo $data['name'];?>
                </a>
              <?php endforeach; ?>
    						<span class="yellow-bar"></span>
    				</div>
    		</header>
    		<div class="tab-content">
          <?php foreach($tabs_object as $data): ?>
            <div id="<?php echo $data['id']; ?>">
              <?php include($data['content_file_name']); ?>
            </div>
          <?php endforeach; ?>
    		</div>
      </div>
  </div>
</div>
<snackbar></snackbar>
