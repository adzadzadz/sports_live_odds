<?php 

namespace src\shortcodes;

use adzmvc\Shortcode;

class LiveOdds extends Shortcode {

  public function run() {
    add_shortcode( 'slo-all', [$this, 'initLiveOdds'] );
    add_shortcode( 'slo-mlb', [$this, 'shortcodeMLB'] );
    add_shortcode( 'slo-nfl', [$this, 'shortcodeNFL'] );
  }

  public function initLiveOdds()
  {
    return $this->render('primary.php',[
      'pluginPath' => $this->pluginPath
    ]);
  }

  public function shortcodeMLB()
  {
    return $this->render('primary/mlb-content.php', ['pluginPath' => $this->pluginPath]);
  }

  public function shortcodeNFL()
  {
    return $this->render('primary/nfl-content.php', ['pluginPath' => $this->pluginPath]);
  }
  
}