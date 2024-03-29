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

include_once 'functions.php';

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