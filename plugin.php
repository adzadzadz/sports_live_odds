<?php 
/*
Plugin Name: Sports Live Odds
Plugin URI: https://www.adriansaycon.com
Description: Description
Version: 0.9.7
Author: Adrian Saycon
Author URI: https://www.adriansaycon.com
*/
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

function pluginprefix_install() {
  // Add commands here
  // clear the permalinks after the post type has been registered
  flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'pluginprefix_install' );

function pluginprefix_deactivation() {
  // clear the permalinks to remove our post type's rules from the database
  flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'pluginprefix_deactivation' );

// Styles and Scripts
function enqueueAssets()
{
  wp_enqueue_style( 'sloBootstrapCss', plugins_url('src/assets/node_modules/bootstrap/dist/css/bootstrap-grid.min.css',__FILE__) ,false, '1.1', 'all');
  wp_enqueue_style( 'sloMainStyles', plugins_url('src/assets/css/styles.css',__FILE__) ,false, '6.1.9', 'all');
  wp_enqueue_script( 'momentJs', plugins_url('src/assets/node_modules/moment/moment.js', __FILE__), null, null, false);
  wp_enqueue_script( 'momentTimezone', plugins_url('src/assets/node_modules//moment-timezone/moment-timezone.js', __FILE__), 'momentJs', null, false);
  wp_enqueue_script( 'sloJs', plugins_url('src/assets/js/main.js', __FILE__), ['jquery'], '6.3.9', false);
  wp_localize_script('sloJs', 'sloData', array(
    'pluginsUrl' => plugins_url(),
  ));
}
add_action( 'wp_enqueue_scripts', 'enqueueAssets' );
add_action( 'admin_enqueue_scripts', 'enqueueAssets' );

if(!function_exists('classAutoLoader')){
  function classAutoLoader($class){
      if ($class == 'Adz') {
        $classFile = plugin_dir_path(__FILE__) . "adzmvc/Adz" . '.php';
      } else {
        $classFile = plugin_dir_path(__FILE__) . str_replace('\\', '/', $class) . '.php';
      }
      if (
        is_file($classFile) &&
        !class_exists($class)
      ) { 
        include $classFile;
      }
  }
}
spl_autoload_register('classAutoLoader');

use src\shortcodes\LiveOdds;
use adzmvc\View;
use src\controllers\AdminController;
use src\controllers\DownloadController;

global $adzSLO;
include 'config.php';
$adzSLO['config'] = $config;

Class Plugin {

  public $pluginPath;
  public $pluginUrl;

  function run()
  {
    include 'config.php';
    $this->pluginPath = plugin_dir_path(__FILE__);
    $this->pluginUrl = plugin_dir_url( __FILE__ );

    $download = new DownloadController;
    $download->pluginPath = $this->pluginPath;
    
    $liveOdds = new LiveOdds;
    $liveOdds->pluginPath = $this->pluginPath;
    $liveOdds->pluginUrl = $this->pluginUrl;
    $liveOdds->config = $config;
    $liveOdds->run();
    
    $admin = new AdminController;
    $admin->pluginPath = $this->pluginPath;
  }

}

$plugin = new Plugin;
$plugin->run();