<?php 

namespace src\controllers;

use adzmvc\Controller;
use adzmvc\View;

class DownloadController extends Controller {

  public function init()
  {
    add_action("wp_ajax_generate_csv", [$this, "generate_csv"]);
    add_action("wp_ajax_nopriv_generate_csv", [$this, "generate_csv"]);
  }

  public function generate_csv() 
  {
    
    // nonce check for an extra layer of security, the function will exit if it fails
    if ( !wp_verify_nonce( $_REQUEST['nonce'], "generate_csv_nonce")) {
       exit("Nothing to see here.");
    }
    
    $sport = $_POST['sport'];
    $season = $_POST['season'];
    $week  = $_POST['week'];
    $type  = $_POST['type'];
    
    if (isset($sport) && isset($season) && isset($week) && isset($type)) {
      switch ($season) {
        case 'POST':
          $season = date('Y') . "POST";
          break;
        case 'PRE':
          $season = date('Y') . "PRE";
        default:
          $season = date('Y') . "REG";
          break;
      }
      echo $this->fetch($sport, $season, $week, $type);
    }

    die();
  }

  public function fetch($sport, $season, $week, $type) 
  {
    global $adzSLO;
    // $config
    $uploadsPath = wp_upload_dir( "slo", false )['path'];
    $uploadsUrl  = wp_upload_dir( "slo", false )['url'];

    $rand = rand(1, 999999999);
    $fileName = [$type => "$sport-spread-" . $rand . ".csv"];
    $filePath = $uploadsPath  . $fileName[$type];

    // Fetch schedules using week's score as line ending indicator
    $schedUrl = "https://api.sportsdata.io/v3/$sport/scores/json/ScoresByWeek/$season/$week?key=" .  $adzSLO['config']['apiKeys']['nfl']['schedule'];
    $currentScheduleResult = \adzmvc\RESTApiHelper::getREST($schedUrl);
    $currentScheduleResultData = \json_decode($currentScheduleResult);

    $oddsDataSet = [];
    foreach ($currentScheduleResultData as $eachScore) {
      $oddsUrl = "https://api.sportsdata.io/v3/$sport/odds/json/GameOddsLineMovement/$eachScore->ScoreID?key=" . $adzSLO['config']['apiKeys']['nfl']['schedule'];
      $oddsResult = \adzmvc\RESTApiHelper::getREST($oddsUrl);
      $oddsData = \json_decode($oddsResult, true);
      $oddsDataSet[] = $oddsData['GameInfo'];
    }

    switch ($type) {
      case 'PointSpread':
        $this->setPointSpread($filePath, $oddsDataSet);
        break;
      
      default:
        # code...
        break;
    }

    return $uploadsUrl . $fileName[$type];
    
  }

  public function setPointSpread($filePath, $data)
  {
    $file = fopen($filePath, "w");
    $spreadHeader = [
      'Week',
      'Date',
      'Bookmaker',
      'Sport',
      'Rotation',
      'Team',
      'Line',
      'Odds',
      'Last Update'
    ];
    fputcsv($file, $spreadHeader);

    $teams = ['Home', 'Away'];

    foreach ($data as $game) {
      foreach ($game['PregameOdds'] as $book) {
        if ($book['Sportsbook'] == 'Pinnacle') {
          foreach($teams as $team) {
            $date = date_create($book['Updated']);
            // Spread
            $format = [
              'Week' => $game['Week'],
              'Date' => date_format($date,"m/d/Y"),
              'Bookmaker' => $book['Sportsbook'],
              'Sport' => 'nfl',
              'Rotation' => $game[$team . "RotationNumber"],
              'Team' => $game[$team . "TeamName"],
              'Line' => $book[$team . "PointSpread"],
              'Odds' => $book[$team . "PointSpreadPayout"],
              'Last Updated' => (new DateTime('America/New_York'))->format('h:i:s')
            ];
            fputcsv($fileSpread, $format);
          }
        }
      }
    }
  }

}
