<div class="accordion-container bmi-mini-accordion">
  <div class="set">
    <button>
      Pages
      <i class="fa fa-plus"></i>
    </button>
    <div class="content">
      <ul class="mini-tabs">
        <li class="mini-tab-link current" data-tab="mini-tab-recent-page">Recent</li>
        <li class="mini-tab-link" data-tab="mini-tab-all-page">All</li>
      </ul>
      <div id="mini-tab-recent-page" class="mini-tab-content current">
        <div class="mini-tab-wrapper">
          <div class="mini-tab-search">
            <input type="search" id="mini-tab-input-page-search-recent" placeholder="Search..">
          </div>
          <ul>
            <?php
            $recent_posts = wp_get_recent_posts(array('post_type' => 'page', 'posts_per_page' => 10,
    'order'          => 'ASC'));
              foreach ($recent_posts as $recent):
                ?>
                <li>
                  <label class="pure-material-checkbox bmi-checkbox">
                    <input type="checkbox" class="bmi-checkbox-page" value="<?php echo $recent["ID"]; ?>">
                    <span><?php echo $recent["post_title"]; ?></span>
                  </label>
                </li>
                <?php
              endforeach;
              wp_reset_query();
             ?>
          </ul>
        </div>
      </div>
      <div id="mini-tab-all-page" class="mini-tab-content">
        <div class="mini-tab-wrapper">
          <div class="mini-tab-search">
            <input type="search" id="mini-tab-input-page-search-all" placeholder="Search..">
          </div>
          <ul>
              <?php
              $recent_posts = get_posts(array('post_type' => 'page',  'numberposts' => -1, 'orderby'        => 'title'));
                foreach ($recent_posts as $recent):
                  ?>
                  <li>
                    <label class="pure-material-checkbox bmi-checkbox">
                      <input type="checkbox" class="bmi-checkbox-page" value="<?php echo $recent->ID; ?>">
                      <span><?php echo $recent->post_title; ?></span>
                    </label>
                  </li>
                  <?php
                endforeach;
                wp_reset_query();
               ?>
          </ul>
        </div>
      </div>
      <div class="mini-tab-action-wrapper">
        <button class="bmi-add-item" data-section="page">Add to <span class="selected-type"></span></button>
      </div>
    </div>
  </div>
  <div class="set">
    <button>
      Posts
      <i class="fa fa-plus"></i>
    </button>
    <div class="content">
      <ul class="mini-tabs">
        <li class="mini-tab-link current" data-tab="mini-tab-recent-posts">Recent</li>
        <li class="mini-tab-link" data-tab="mini-tab-all-posts">All</li>
      </ul>
      <div id="mini-tab-recent-posts" class="mini-tab-content current">
        <div class="mini-tab-wrapper">
          <div class="mini-tab-search">
            <input type="search" id="mini-tab-input-posts-search-all" placeholder="Search..">
          </div>
          <ul>
            <?php
            $recent_posts = wp_get_recent_posts(array('post_type' => 'post', 'posts_per_page' => 10,
    'order'          => 'ASC'));
              foreach ($recent_posts as $recent):
                ?>
                <li>
                  <label class="pure-material-checkbox bmi-checkbox">
                    <input type="checkbox" class="bmi-checkbox-post" value="<?php echo $recent["ID"]; ?>">
                    <span><?php echo $recent["post_title"]; ?></span>
                  </label>
                </li>
                <?php
              endforeach;
              wp_reset_query();
             ?>
          </ul>
        </div>
      </div>
      <div id="mini-tab-all-posts" class="mini-tab-content">
        <div class="mini-tab-wrapper">
          <div class="mini-tab-search">
            <input type="search" id="mini-tab-input-posts-search-all" placeholder="Search..">
          </div>
          <ul>
              <?php
              $recent_posts = get_posts(array('post_type' => 'post',  'numberposts' => -1, 'orderby' => 'title'));
                foreach ($recent_posts as $recent):
                  ?>
                  <li>
                    <label class="pure-material-checkbox bmi-checkbox">
                      <input type="checkbox" class="bmi-checkbox-post" value="<?php echo $recent->ID; ?>">
                      <span><?php echo $recent->post_title; ?></span>
                    </label>
                  </li>
                  <?php
                endforeach;
                wp_reset_query();
               ?>
          </ul>
        </div>
      </div>
      <div id="mini-tab-search-posts" class="mini-tab-content">
        <ul>
          <li>
            <label for="mini-tab-input-post-search">Search</label>
            <input type="search" id="mini-tab-input-post-search" />
          </li>
        </ul>
      </div>
      <div class="mini-tab-action-wrapper">
        <button class="bmi-add-item" data-section="post">Add to <span class="selected-type"></span></button>
      </div>
    </div>
  </div>
  <div class="set" style="display:none">
    <button>
      Categories
      <i class="fa fa-plus"></i>
    </button>
    <div class="content">
      <ul class="mini-tabs">
        <li class="mini-tab-link" data-tab="mini-tab-all-cat">All</li>
        <li class="mini-tab-link" data-tab="mini-tab-search-cat">Search</li>
      </ul>
      <div id="mini-tab-all-cat" class="mini-tab-content current">
        <div class="mini-tab-wrapper">
          <div class="mini-tab-search">
            <input type="search" id="mini-tab-input-cat-search-all" placeholder="Search..">
          </div>
          <ul>
              <?php
              $recent_posts = get_posts(array('post_type' => 'page'));
                foreach ($recent_posts as $recent):
                  ?>
                  <li>
                    <label class="pure-material-checkbox bmi-checkbox">
                      <input type="checkbox" class="bmi-checkbox-cat" value="<?php echo $recent->ID; ?>">
                      <span><?php echo $recent->post_title; ?></span>
                    </label>
                  </li>
                  <?php
                endforeach;
                wp_reset_query();
               ?>
          </ul>
        </div>
      </div>
      <div class="mini-tab-action-wrapper">
        <button class="bmi-add-item" data-section="cat">Add to <span class="selected-type"></span></button>
      </div>
    </div>
  </div>
</div>
