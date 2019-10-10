<?php 

namespace src\shortcodes;

use adzmvc\Shortcode;

class LiveOdds extends Shortcode {

  public $pluginUrl;

  public function run() {
    add_shortcode( 'slo-csv-setup', [$this, 'shortcodeCSV'] );
    add_shortcode( 'slo-nfl-csv', [$this, 'downloadNFLDataCsv'] );
    add_shortcode( 'slo-all', [$this, 'initLiveOdds'] );
    add_shortcode( 'slo-nba', [$this, 'shortcodeNBA'] );
    add_shortcode( 'slo-mlb', [$this, 'shortcodeMLB'] );
    add_shortcode( 'slo-nhl', [$this, 'shortcodeNHL'] );
    add_shortcode( 'slo-nfl', [$this, 'shortcodeNFL'] );
    add_shortcode( 'slo-ncaaf', [$this, 'shortcodeNCAAF'] );
  }

  public function initLiveOdds()
  {
    return $this->render('primary.php',[
      'pluginPath' => $this->pluginPath
    ]);
  }

  public function downloadNFLDataCsv()
  {    
    return $this->render('download/nfl-csv.php', [
      'pluginPath' => $this->pluginPath,
    ]);
  }

  public function shortcodeCSV()
  {
    return $this->render('csv-setup.php', ['pluginPath' => $this->pluginPath]);
  }
  
  public function shortcodeNBA()
  {
    return $this->render('primary/nba-content.php', ['pluginPath' => $this->pluginPath]);
  }

  public function shortcodeMLB()
  {
    return $this->render('primary/mlb-content.php', ['pluginPath' => $this->pluginPath]);
  }

  public function shortcodeNHL()
  {
    return $this->render('primary/nhl-content.php', ['pluginPath' => $this->pluginPath]);
  }

  public function shortcodeNFL()
  {
    return $this->render('primary/nfl-content.php', ['pluginPath' => $this->pluginPath]);
  }

  public function shortcodeNCAAF()
  {
    return $this->render('primary/ncaaf-content.php', ['pluginPath' => $this->pluginPath]);
  }
  
}