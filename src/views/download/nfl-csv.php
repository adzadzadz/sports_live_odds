<?php

$uploadsPath = wp_upload_dir( "slo", false )['path'];
$uploadsUrl  = wp_upload_dir( "slo", false )['url'];

$fileName = "nfl-" . rand(1, 999999999) . ".csv";
$fullPath = $uploadsPath  . $fileName;

$currentUrl = "https://api.sportsdata.io/v3/nfl/scores/json/Timeframes/current?key=" .  $this->config['apiKeys']['nfl']['schedule'];
$currentScheduleResult = \adzmvc\RESTApiHelper::getREST($currentUrl);
$currentScheduleResultData = \json_decode($currentScheduleResult);

// var_dump($currentScheduleResultData);
$oddsUrl = "https://api.sportsdata.io/v3/nfl/odds/json/GameOddsByWeek/" . $currentScheduleResultData[0]->ApiSeason . "/" . $currentScheduleResultData[0]->ApiWeek . "?key=" . $this->config['apiKeys']['nfl']['liveOdds'];
$oddsResult = \adzmvc\RESTApiHelper::getREST($oddsUrl);
$oddsData = \json_decode($oddsResult, true);

$teams = ['Home', 'Away'];

$f = fopen($fullPath, "w");
$header = [
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
fputcsv($f, $header);
foreach ($oddsData as $game) {
  foreach ($game['PregameOdds'] as $book) {
    if ($book['Sportsbook'] == 'Pinnacle') {
      foreach($teams as $team) {
        $format = [
          'Week' => $game['Week'],
          'Date' => $game['DateTime'],
          'Bookmaker' => $book['Sportsbook'],
          'Sport' => 'nfl',
          'Rotation' => $game[$team . "RotationNumber"],
          'Team' => $game[$team . "TeamName"],
          'Line' => $book[$team . "PointSpread"],
          'Odds' => $book[$team . "MoneyLine"],
          'Last Updated' => "Realtime-TODO"
        ];
        fputcsv($f, $format);
      }
    }
  }
}

$fullUrl = $uploadsUrl . $fileName;

header("Expires: 0");
header("Cache-Control: no-cache, no-store, must-revalidate"); 
header('Cache-Control: pre-check=0, post-check=0, max-age=0', false); 
header("Pragma: no-cache");	
// header("Content-type: {$content_type}");
header("Content-Disposition:attachment; filename={$fullUrl}");
header("Content-Type: application/force-download");