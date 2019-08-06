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
    add_action( 'admin_menu', 'setPluginMenu' );

    /** Step 1. */
    function setPluginMenu() 
    {
      add_menu_page('Live Odds Dashboard', 'Live Odds', 'manage_options', 'slo-menu-page', 'showDashboardPage');
      add_submenu_page( 'slo-menu-page', 'Database Update', 'Update Database', 'manage_options', 'slo-update-db', 'updateDatabase' );
      add_submenu_page( 'slo-menu-page', 'Data Update', 'Update Sports Data', 'manage_options', 'slo-update-data', 'updateSportsData' );
    }

    function showDashboardPage() 
    {
      if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
      }
      include 'config.php';
      $view = new View;
      $view->config = $config;
      $view->pluginPath = plugin_dir_path(__FILE__);
      echo $view->render('admin/optionsmenu.php');
    }

    /**
     * TODOS: 
     * - Fetch schedule data
     * - Store minimal required data to db
     * - use stored schedule to prepopulate game odds settings
     * - make everything reusable for futureproffing
     * - speed up process
     */
    function updateSportsData()
    {
      if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
      }
      include 'config.php';
      $view = new View;
      $view->config = $config;
      $view->pluginPath = plugin_dir_path(__FILE__);
      echo $view->render('admin/updatesportsdata.php');
    }

    function updateDatabase() 
    {
      if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
      }
      global $wpdb;

      $charset_collate = $wpdb->get_charset_collate();
      
      $sportTable = 'slo_plugin_sport';
      if($wpdb->get_var("SHOW TABLES LIKE '$sportTable'") !==  $sportTable) {
        $sqlGame = "CREATE TABLE $sportTable (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          game_code tinytext NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sqlGame);
      }

      $sportScheduleTable = 'slo_plugin_sport_schedules';
      if($wpdb->get_var("SHOW TABLES LIKE '$sportScheduleTable'") !==  $sportScheduleTable) {
        $sqlSchedule = "CREATE TABLE $sportScheduleTable (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          sport_id mediumint(9) NOT NULL,
          date tinytext NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sqlSchedule);
      }
      
      add_option( 'slo_db_version', '0.1.0' );

      echo "Database Updated...";
    }
  }

}

$plugin = new Plugin;
$plugin->run();