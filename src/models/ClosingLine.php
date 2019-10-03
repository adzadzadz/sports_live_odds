<?php 

namespace src\models;

use adzmvc\Model;

class ClosingLine extends Model {

  public static function getTable()
  {
    return 'slo_plugin_closing_line';
  }

  public static function createTable()
  {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    
    $closingLine = self::getTable();
    if($wpdb->get_var("SHOW TABLES LIKE '$closingLine'") !==  $closingLine) {
      $sqlGame = "CREATE TABLE $closingLine (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        sport_code varchar(50) NOT NULL,
        week varchar(50) NOT NULL,
        date varchar(50) NOT NULL,
        bookmaker varchar(50) NOT NULL,
        rotation varchar(50) NOT NULL,
        team varchar(50) NOT NULL,
        away varchar(50) NOT NULL,
        home varchar(50) NOT NULL,
        total varchar(50) NOT NULL,
        moneyline varchar(50) NOT NULL,
        line varchar(50) NOT NULL,
        odds varchar(50) NOT NULL,
        last_updated varchar(50) NOT NULL,
        PRIMARY KEY  (id)
      ) $charset_collate;";
      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta($sqlGame);

      add_option( 'slo_db_closing_Line_version', '0.1.0' );
    }
  }

}