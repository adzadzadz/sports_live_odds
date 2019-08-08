<?php 
/*
Plugin Name: Sports Live Odds
Plugin URI: https://www.adriansaycon.com
Description: Description
Version: 0.1.0
Author: Adrian Saycon
Author URI: https://www.adriansaycon.com
*/
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

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

use src\shortcodes\LiveOdds;
use adzmvc\View;
use src\controllers\AdminController;

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
    
    $admin = new AdminController;
    $admin->pluginPath = $this->pluginPath;
  }


}

$plugin = new Plugin;
$plugin->run();