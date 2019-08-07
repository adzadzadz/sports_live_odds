<?php 

namespace src\models;

use adzmvc\Model;

class Sport extends Model {

  public static $sports = ['NFL', 'NCAAF', 'MLB', 'NBA', 'WNBA', 'NCAAB', 'NHL'];

  public static function getTable()
  {
    return 'slo_plugin_sport';
  }

}