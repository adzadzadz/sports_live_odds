<?php 

namespace adzmvc;

class View extends Core {

  public function render($path, $attr = [])
  {
    ob_start();
    extract($attr);
    // var_dump($this);
    include($this->pluginPath . "src/views/" . $path);
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
  }

}