<?php 
/*
Plugin Name: Sports Live Odds
Plugin URI: https://www.adriansaycon.com
Description: Description
Version: 0.1.0
Author: Adrian Saycon
Author URI: https://www.adriansaycon.com
*/
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'functions.php';


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

use Adz;
use src\shortcodes\LiveOdds;
use adzmvc\View;

Class Plugin {

  public $pluginPath;

  function run()
  {
    include 'config.php';
    $this->pluginPath = plugin_dir_path(__FILE__);
    $liveOdds = new LiveOdds;
    $liveOdds->pluginPath = $this->pluginPath;
    $liveOdds->config = $config;
    $liveOdds->run();
    $this->initAdminMenu();
  }

  function initAdminMenu()
  {
    /** Step 2 (from text above). */
    add_action( 'admin_menu', 'my_plugin_menu' );

    /** Step 1. */
    function my_plugin_menu() {
      add_options_page( 'Slo Options', 'Live Odds', 'manage_options', 'slo-options-menu', 'my_plugin_options' );
    }

    /** Step 3. */
    function my_plugin_options() {
      if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
      }
      include 'config.php';
      $view = new View;
      $view->config = $config;
      $view->pluginPath = plugin_dir_path(__FILE__);
      echo $view->render('admin/optionsmenu.php');
    }
  }

}

$plugin = new Plugin;
$plugin->run();