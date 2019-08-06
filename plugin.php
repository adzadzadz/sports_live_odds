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
      // $class = strtolower($class);
      $classFile = plugin_dir_path(__FILE__) . str_replace('\\', '/', $class) . '.php';
      // var_dump($classFile);
      // var_dump(is_file($classFile));
      if (
        is_file($classFile) &&
        !class_exists($class)
      ) { 
        // var_dump($classFile);
        include $classFile;
      }
  }
}
spl_autoload_register('classAutoLoader');

use src\shortcodes\LiveOdds;

Class Plugin {

  function run()
  {
    $liveOdds = new LiveOdds;
    $liveOdds->pluginPath = plugin_dir_path(__FILE__);
    $liveOdds->run();
  }

}

$plugin = new Plugin;
$plugin->run();