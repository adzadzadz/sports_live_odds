<?php 

namespace src\shortcodes;

use adzmvc\Shortcode;

class LiveOdds extends Shortcode {

  public function run() {
    add_shortcode( 'slo-live-odds', [$this, 'initLiveOdds'] );
  }

  public function initLiveOdds()
  {
    return $this->render('primary.php',[
      'pluginPath' => $this->pluginPath
    ]);
  }
  
}