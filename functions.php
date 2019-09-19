<?php 

function pluginprefix_install() {
  var_dump("activate");
  // Add commands here
  // clear the permalinks after the post type has been registered
  flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'pluginprefix_install' );

function pluginprefix_deactivation() {
  var_dump("deactivate");
  // clear the permalinks to remove our post type's rules from the database
  flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'pluginprefix_deactivation' );

// Styles and Scripts
function enqueueAssets()
{
  wp_enqueue_style( 'sloBootstrapCss', plugins_url('src/assets/node_modules/bootstrap/dist/css/bootstrap-grid.min.css',__FILE__) ,false, '1.1', 'all');
  wp_enqueue_style( 'sloMainStyles', plugins_url('src/assets/css/styles.css',__FILE__) ,false, '6.1.7', 'all');
  wp_enqueue_script( 'momentJs', plugins_url('src/assets/node_modules/moment/moment.js', __FILE__), null, null, false);
  wp_enqueue_script( 'momentTimezone', plugins_url('src/assets/node_modules//moment-timezone/moment-timezone.js', __FILE__), 'momentJs', null, false);
  wp_enqueue_script( 'sloJs', plugins_url('src/assets/js/main.js', __FILE__), ['jquery'], '6.3.2', false);
  wp_localize_script('sloJs', 'sloData', array(
    'pluginsUrl' => plugins_url(),
  ));
}
add_action( 'wp_enqueue_scripts', 'enqueueAssets' );
add_action( 'admin_enqueue_scripts', 'enqueueAssets' );