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
  wp_enqueue_style( 'sloBootstrapCss', plugins_url('src/assets/node_modules/bootstrap/dist/css/bootstrap.min.css',__FILE__) ,false, '1.1', 'all');
  wp_enqueue_script( 'sloBootstrapJs', plugins_url('src/assets/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',__FILE__) ,['sloJquery'], '1.1', 'all');
  wp_enqueue_script( 'sloJquery', plugins_url('src/assets/node_modules/jquery/dist/jquery.min.js', __FILE__), ['jquery'], 1.1, true);
}
add_action( 'wp_enqueue_scripts', 'enqueueAssets' );
add_action( 'admin_enqueue_scripts', 'enqueueAssets');