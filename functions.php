<?php 

function pluginprefix_install() {
  // Add commands here
  createTables();
  // clear the permalinks after the post type has been registered
  flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'pluginprefix_install' );

function createTables() {
  global $wpdb;

  $charset_collate = $wpdb->get_charset_collate();
  
  $sqlGame = "CREATE TABLE 'slo_plugin_sport' (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    game_code tinytext NOT NULL,
    PRIMARY KEY  (id)
  ) $charset_collate;";

  $sqlSchedule = "CREATE TABLE 'slo_plugin_sport_schedules' (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    sport_id mediumint(9) NOT NULL,
    date tinytext NOT NULL,
    PRIMARY KEY  (id)
  ) $charset_collate;";

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql );
}


function pluginprefix_deactivation() {
  // clear the permalinks to remove our post type's rules from the database
  flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'pluginprefix_deactivation' );


// Styles and Scripts
wp_enqueue_style( 'sloBootstrapCss', plugins_url('src/assets/node_modules/bootstrap/dist/css/bootstrap.min.css',__FILE__) ,false, '1.0', 'all');
wp_enqueue_script( 'sloBootstrapJs', plugins_url('src/assets/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',__FILE__) ,['sloJquery'], '1.0', 'all');
wp_enqueue_script( 'sloJquery',plugins_url('src/assets/node_modules/jquery/dist/jquery.min.js', __FILE__), array ( 'jquery' ), 1.0, true);
// wp_enqueue_script( 'sloAjax',plugins_url('src/assets/js/ajax.js', __FILE__), array ( 'sloJquery' ), 1.0, true);