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
