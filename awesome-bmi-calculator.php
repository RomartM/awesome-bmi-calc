<?php
/*
Plugin Name: Awesome BMI Calculator
PLugin URI: https://demo.plugins.code
Description: Allows to Calculate your Body Mass Index with Posts Suggestion
Version: 1.0
Licence: MIT License
*/

defined('ABSPATH') or die('No script kiddies please!');
define('BMI_DB_PREFIX', 'abc_');
register_activation_hook( __FILE__, 'bmi_db_install' );
register_activation_hook( __FILE__, 'bmi_init_db_data' );


// Global DB Version
global $bmi_db_version;
global $bmi_db_plugin_prefix;

$bmi_db_version = '1.2';

// Database Installation
function bmi_db_install(){
  global $wpdb;
  global $bmi_db_version;

  $charset_collate = $wpdb->get_charset_collate();

  $table_standards = $wpdb->prefix. BMI_DB_PREFIX .'standards';
  $table_customiztion = $wpdb->prefix. BMI_DB_PREFIX . 'customization';
  $table_settings = $wpdb->prefix. BMI_DB_PREFIX . 'settings';


  $fr_pk = $table_customiztion."_". $table_standards."_id_fk";

  $sql_standards = "CREATE TABLE IF NOT EXISTS $table_standards (
	         `id` INT(30) NOT NULL AUTO_INCREMENT,
	         `name` VARCHAR(30) DEFAULT '',
	         `range` VARCHAR(30) DEFAULT '',
           `color` VARCHAR(30) DEFAULT '',
	         PRIMARY KEY (`id`),
           CONSTRAINT $table_standards UNIQUE (`name`)
         ) $charset_collate;";

  $sql_customization = "CREATE TABLE IF NOT EXISTS $table_customiztion (
          `id` INT NOT NULL AUTO_INCREMENT,
          `postype_id` VARCHAR(50),
          `postype` VARCHAR(50),
          `type` INT NOT NULL,
          `data_id` VARCHAR(30) DEFAULT NULL,
          `sequence` INT DEFAULT NULL,
          PRIMARY KEY (`id`),
          CONSTRAINT $table_standards UNIQUE (`data_id`),
          CONSTRAINT $fr_pk FOREIGN KEY (`type`) REFERENCES $table_standards (`id`)
  ) $charset_collate; ";

  $sql_settings = "CREATE TABLE IF NOT EXISTS $table_settings (
          `id` INT NOT NULL AUTO_INCREMENT,
          `item` VARCHAR(30),
          `value` TEXT,
    PRIMARY KEY (`id`)
  ) $charset_collate;";

  require_once(ABSPATH. 'wp-admin/includes/upgrade.php');

  $wpdb->query($sql_standards);
  $wpdb->query($sql_customization);
  $wpdb->query($sql_settings);

  add_option('bmi_db_version', $bmi_db_version);
}

// Plugin Initial Data Initialazation
function bmi_init_db_data(){
  global $wpdb;

  $table_standards = $wpdb->prefix. BMI_DB_PREFIX . 'standards';

  $wpdb->query("INSERT IGNORE INTO $table_standards
            (`name`, `range`, `color`)
            VALUES
            ('Severely Underweight', 'inf:16.5', '#607D8B:#9E9E9E'),
            ('Underweight', '16.5:18.5', '#607D8B:#1CBBB4'),
            ('Normal', '18.5:25', '#8EF378:#1CBBB4'),
            ('Overweight', '25:30', '#E95F62:#F286A0'),
            ('Obese', '30:inf', '#FDB9BE:#E95A7D')");
}

// Frontend Asset loads
function load_widget_stylesheet()
{
    wp_enqueue_style('abc-widget-styles', plugins_url('css/awesome_bmi_widget_style.css', __FILE__));
}
function load_stylesheet()
{
    wp_enqueue_style('abc-styles', plugins_url('css/awesome_bmi_style.css', __FILE__));
}

function load_javascript()
{
    wp_enqueue_script('abc_main', plugins_url('js/awesome_bmi_app.js', __FILE__), array('jquery'));
    wp_localize_script('abc_main', 'abc_manifest', array( 'state_manifest' => get_state_data(), 'suggested_links'=>get_customizer_links()));
}
// Admin Asset load
function load_admin_stylesheet()
{
    wp_enqueue_style('jquery-ui-css', plugins_url('css/lib/jquery-ui.css', __FILE__));
    wp_enqueue_style('snackbar-css', plugins_url('css/lib/snackbar.css', __FILE__));
    wp_enqueue_style('material-icon-css', plugins_url('css/lib/icon.css', __FILE__));
    wp_enqueue_style('abc-admin-styles', plugins_url('css/awesome_bmi_dashboard_style.css', __FILE__));
}

function load_admin_javascript()
{
    wp_enqueue_script('jquery-ui-js', plugins_url('js/lib/jquery-ui.js', __FILE__), array('jquery'));
    // Load Snackbar Plugin lib
    wp_enqueue_script('abc_admin_core_snackbar', plugins_url('js/lib/snackbar.js', __FILE__), array('jquery'));
    // Load Core ABC/BMI class
    wp_enqueue_script('abc_admin_core_class', plugins_url('js/awesome_bmi_core.js', __FILE__), array('jquery'));
    // Load BMI Dashboard script
    wp_enqueue_script('abc_admin_main', plugins_url('js/awesome_bmi_dashboard.js', __FILE__), array('jquery'));
    // Load Ajax Manifest and Customizer JSON data.
    wp_localize_script('abc_admin_main', 'abc_manifest', array( 'ajax_url' => admin_url('admin-ajax.php'), 'customizer_data'=>get_customizer_data(), 'state_manifest' => get_state_data()));
}


function awesome_bmi_calculator()
{
    require('template/bmi_form.php');
}

// Creating the widget
class awesome_bmi_widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(

        // Base ID of your widget
        'abc_widget',

        // Widget name will appear in UI
        __('BMI Calculator Widget', 'abc_widget_domain'),

        // Widget description
        array( 'description' => __('Calculate Body Mass Index', 'abc_widget_domain'), )
        );
    }

    // Creating widget front-end

    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);

        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (! empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // This is where you run the code and display the output
        awesome_bmi_calculator();
        echo $args['after_widget'];
    }

    // Widget Backend
    public function form($instance)
    {
        if (isset($instance[ 'title' ])) {
            $title = $instance[ 'title' ];
        } else {
            $title = __('New title', 'abc_widget_domain');
        }
        // Widget admin form?>
<p>
<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
</p>
<?php
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (! empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
} // Class abc_widget ends here

// Plugin Admin Dashbaord
function abc_admin_dashboard()
{
    add_menu_page('Awesome BMI Dashboard', 'Awesome BMI', 'manage_options', 'awesome-bmi', 'abc_admin_dashboard_init');
}

// Plugin Admin Dashbaord Initiator
function abc_admin_dashboard_init()
{
    require('dashboard.php');
}

// Register and load the widget
function abc_load_widget()
{
    register_widget('awesome_bmi_widget');
}


// Get Posts by AJAX Implementation
function get_posts_data()
{
    $args = array(
         'post_type'   => $_POST['post_type'],
         'order'          => 'ASC',
         'orderby'        => 'title'
  );
    $allowed_keys = ['post_title', 'post_name', 'ID'];
    $queried = get_posts($args);
    echo "{\"data\":".json_encode($queried)."}";
    wp_die();
}

function get_state_data(){
  global $wpdb;
  $table_customization = $wpdb->prefix. BMI_DB_PREFIX . 'standards';
  $results = $wpdb->get_results("SELECT * FROM $table_customization");
  return json_encode($results);
  wp_die();
}

function get_customizer_links(){
  global $wpdb;
  $table_customization = $wpdb->prefix. BMI_DB_PREFIX . 'customization';
  $results = $wpdb->get_results("SELECT * FROM $table_customization ORDER BY `sequence` ASC");
  $data = array();
  for ($i=0; $i < count($results); $i++) {
    array_push($data, (object)[
        'sid' => $results[$i]->sequence,
        'type' => $results[$i]->type,
        'permalink' => (object)[
        'link' => get_post_permalink($results[$i]->postype_id),
        'title' => get_the_title($results[$i]->postype_id),
        'thumbnail' => get_the_post_thumbnail($results[$i]->postype_id)
      ]
    ]);
  }
  return json_encode($data);
  wp_die();
}

function get_customizer_data(){
  global $wpdb;
  $table_customization = $wpdb->prefix. BMI_DB_PREFIX . 'customization';
  $results = $wpdb->get_results("SELECT * FROM $table_customization ORDER BY `sequence` ASC");
  return json_encode($results);
  wp_die();
}

function save_customizer_data(){
  global $wpdb;
  $raw_data = $_POST['data'];
  $deleted_items = $_POST['deleted_items'];
  $table_customization = $wpdb->prefix. BMI_DB_PREFIX . 'customization';
  $temp = "";
  for ($parent_data=0; $parent_data < count($raw_data) ; $parent_data++) {
    for ($item_data=0; $item_data < count($raw_data[$parent_data]["id_list"]) ; $item_data++) {
      $temp .= "('".$raw_data[$parent_data]["id_list"][$item_data]."',
      '".apply_filters('wp_abc_customization_wp_abc_standards_id_fk', $raw_data[$parent_data]["state_type_id"])."',
      '".$raw_data[$parent_data]["postype"]."',
      '".bin2hex($raw_data[$parent_data]["id_list"][$item_data].''.apply_filters('wp_abc_customization_wp_abc_standards_id_fk', $raw_data[$parent_data]["state_type_id"]).$raw_data[$parent_data]["postype"])."',
      '".$item_data."' )";
      if (count($raw_data[$parent_data]["id_list"])!==($item_data+1)) {
        $temp .= ",";
      }
    }
    $wpdb->query("INSERT IGNORE INTO $table_customization
              (`postype_id`, `type`, `postype`, `data_id`, `sequence`)
              VALUES $temp ON DUPLICATE KEY UPDATE
              `sequence`=VALUES(`sequence`),
              `postype_id`=VALUES(`postype_id`),
              `type`=VALUES(`type`),
              `postype`=VALUES(`postype`),
              `data_id`=VALUES(`data_id`)");
    $temp = "";
  }
  for ($item_to_delete=0; $item_to_delete < count($deleted_items); $item_to_delete++) {
      $wpdb->query("DELETE FROM $table_customization WHERE data_id='".bin2hex($deleted_items[$item_to_delete])."';");
  }
  echo json_encode(array("status"=>200));
  wp_die();
}

add_action('wp_ajax_save_customizer', 'save_customizer_data');

add_action('wp_ajax_get_posts', 'get_posts_data');

add_action('admin_menu', 'abc_admin_dashboard');

add_action('widgets_init', 'abc_load_widget');

// Add to WP Admin enqueue for admin users
add_action('admin_enqueue_scripts', 'load_admin_javascript');
add_action('admin_enqueue_scripts', 'load_admin_stylesheet');

// Add to WP enqueue for public users
add_action('wp_enqueue_scripts', 'load_javascript');
add_action('wp_enqueue_scripts', 'load_stylesheet');
add_action('wp_enqueue_scripts', 'load_widget_stylesheet');

add_shortcode('awesome_bmi', 'awesome_bmi_calculator');

 ?>
