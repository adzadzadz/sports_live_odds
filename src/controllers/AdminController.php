<?php 

namespace src\controllers;

use adzmvc\Controller;
use adzmvc\View;

class AdminController extends Controller {

  public function init()
  {
    add_action( 'admin_menu', [$this, 'setMenu'] );
  }

  public function setMenu()
  {
    add_menu_page('Live Odds Dashboard', 'Live Odds', 'manage_options', 'slo-menu-page', [$this, 'actionDashboard']);
    add_submenu_page( 'slo-menu-page', 'Database Update', 'Update Database', 'manage_options', 'slo-update-db', [$this, 'actionUpdateDB'] );
    add_submenu_page( 'slo-menu-page', 'Data Update', 'Update Sports Data', 'manage_options', 'slo-update-data', [$this, 'actionUpdateSportsData'] );
    return true;
  }

  public function actionDashboard()
  {
    if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    include "$this->pluginPath/config.php";
    $view = new View;
    $view->config = $config;
    $view->pluginPath = $this->pluginPath;
    echo $view->render('admin/optionsmenu.php');
  }

  public function actionUpdateDB()
  {
    if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    
    $sportTable = \src\models\Sport::getTable();
    if($wpdb->get_var("SHOW TABLES LIKE '$sportTable'") !==  $sportTable) {
      $sqlGame = "CREATE TABLE $sportTable (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        sport_code tinytext NOT NULL,
        PRIMARY KEY  (id)
      ) $charset_collate;";
      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta($sqlGame);

      foreach(\src\models\Sport::$sports as $sport) {
        $wpdb->insert( $sportTable, [
          'sport_code' => $sport
        ] );
      }      
    }

    $sportScheduleTable = \src\models\SportSchedule::getTable();
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

  /**
   * TODOS: 
   * - Fetch schedule data
   * - Store minimal required data to db
   * - use stored schedule to prepopulate game odds settings
   * - make everything reusable for futureproffing
   * - speed up process
   */
  public function actionUpdateSportsData()
  {
    if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    include "$this->pluginPath/config.php";
    $view = new View;
    $view->config = $config;
    $view->pluginPath = $this->pluginPath;

    echo "Please wait while we store the schedule data into our database.";
    
    $result = \adzmvc\RESTApiHelper::getREST("https://api.sportsdata.io/v3/mlb/scores/json/Games/" . date('Y'), ['key' =>  $config['apiKeys']['mlb']]);
    $data = \json_decode($result);

    global $wpdb;
    $sportTable = \src\models\Sport::getTable();
    $scheduleTable = \src\models\SportSchedule::getTable();

    $sportResult = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $sportTable WHERE sport_code = %s", ['MLB']) );
    if (!$sportResult) {
      echo "Sport Code not found. Contact Dev.";
      return;
    }
    
    $wpdb->query("TRUNCATE TABLE $scheduleTable");
    foreach ($data as $game) {
      $wpdb->insert($scheduleTable, [
        'sport_id' => $sportResult[0]->id,
        'date'     => preg_replace('/T.+/', '', $game->Day)
      ]);
    }

    echo "All data has been saved.";
    // echo $view->render('admin/updatesportsdata.php', ['result' => $result]);
  }

}