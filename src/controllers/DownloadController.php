<?php 

namespace src\controllers;

use adzmvc\Controller;
use adzmvc\View;

class DownloadController extends Controller {

  public function init()
  {
    add_action("wp_ajax_generate_csv", "actionGenerateCsv");
    add_action("wp_ajax_nopriv_generate_csv", "actionGenerateCsv");
  }

  public function actionGenerateCsv() {
   
    // nonce check for an extra layer of security, the function will exit if it fails
    if ( !wp_verify_nonce( $_REQUEST['nonce'], "generate_csv_nonce")) {
       exit("Nothing to see here.");
    }
    
    echo "adz ajax working!";

    // don't forget to end your scripts with a die() function - very important
    die();
  }

}