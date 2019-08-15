<?php 

namespace src\controllers;

use adzmvc\Controller;
use adzmvc\View;

class AdminController extends Controller {

  private $sportTable;
  private $scheduleTable;

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
   * - create initial UI layout
   * - fetch RESTApi json (Frontend - using preloaded settings)
   * - set final UI layout
   * - clean data
   * - Refactor (Priority): make everything reusable for futureproofing
   * - Refactor: speed up process
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

    global $wpdb;
    $this->sportTable = \src\models\Sport::getTable();
    $this->scheduleTable = \src\models\SportSchedule::getTable();

    $wpdb->query("TRUNCATE TABLE $this->scheduleTable");

    $this->updateMLBSchedule($wpdb, $config);
    $this->updateNFLSchedule($wpdb, $config);
    echo "All data has been saved.";
    // echo $view->render('admin/updatesportsdata.php', ['result' => $result]);
  }

  private function updateNFLSchedule($wpdb, $config)
  {
    // https://api.sportsdata.io/v3/nfl/scores/json/Schedules/2019PRE?key=8d83eeb36ceb4cee8072a94f7f85f0e1
    $result = \adzmvc\RESTApiHelper::getREST("https://api.sportsdata.io/v3/nfl/scores/json/Schedules/" . date('Y') . "PRE", ['key' =>  $config['apiKeys']['nfl']['schedule']]);
    $data = \json_decode($result);

    $sportResult = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $this->sportTable WHERE sport_code = %s", ['NFL']) );
    if (!$sportResult) {
      echo "Sport Code not found. Contact Dev.";
      return;
    }
    
    $lastSavedDate = null;
    foreach ($data as $game) {
      if ($game->Day != null) {
        $validDate = preg_replace('/T.+/', '', $game->Day);
        if ($lastSavedDate !== $validDate) {
          $wpdb->insert($this->scheduleTable, [
            'sport_id' => $sportResult[0]->id,
            'date'     => $validDate
          ]);
        }
        $lastSavedDate = $validDate;
      }
    }

    $result = \adzmvc\RESTApiHelper::getREST("https://api.sportsdata.io/v3/nfl/scores/json/Schedules/" . date('Y'), ['key' =>  $config['apiKeys']['nfl']['schedule']]);
    $data = \json_decode($result);

    $sportResult = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $this->sportTable WHERE sport_code = %s", ['NFL']) );
    if (!$sportResult) {
      echo "Sport Code not found. Contact Dev.";
      return;
    }
    
    $lastSavedDate = null;
    foreach ($data as $game) {
      if ($game->Day != null) {
        $validDate = preg_replace('/T.+/', '', $game->Day);
        if ($lastSavedDate !== $validDate) {
          $wpdb->insert($this->scheduleTable, [
            'sport_id' => $sportResult[0]->id,
            'date'     => $validDate
          ]);
        }
        $lastSavedDate = $validDate;
      }
    }
  }

  private function updateMLBSchedule($wpdb, $config)
  {
    $result = \adzmvc\RESTApiHelper::getREST("https://api.sportsdata.io/v3/mlb/scores/json/Games/" . date('Y'), ['key' =>  $config['apiKeys']['mlb']['schedule']]);
    $data = \json_decode($result);

    $sportResult = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $this->sportTable WHERE sport_code = %s", ['MLB']) );
    if (!$sportResult) {
      echo "Sport Code not found. Contact Dev.";
      return;
    }
    
    $lastSavedDate = null;
    foreach ($data as $game) {
      if ($game->Day != null) {
        $validDate = preg_replace('/T.+/', '', $game->Day);
        if ($lastSavedDate !== $validDate) {
          $wpdb->insert($this->scheduleTable, [
            'sport_id' => $sportResult[0]->id,
            'date'     => $validDate
          ]);
        }
        $lastSavedDate = $validDate;
      }
    }
  }

}