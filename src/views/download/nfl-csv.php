<?php

$uploadsPath = wp_upload_dir( "slo", false )['path'];
$uploadsUrl  = wp_upload_dir( "slo", false )['url'];

$rand = rand(1, 999999999);
$fileNameSpread = "nfl-spread-" . $rand . ".csv";
$fileNameMoneyLine = "nfl-moneyline-" . $rand . ".csv";
$fileNameTotal = "nfl-total-" . $rand . ".csv";
$fullPath = $uploadsPath  . $fileName;

$currentUrl = "https://api.sportsdata.io/v3/nfl/scores/json/Timeframes/current?key=" .  $this->config['apiKeys']['nfl']['schedule'];
$currentScheduleResult = \adzmvc\RESTApiHelper::getREST($currentUrl);
$currentScheduleResultData = \json_decode($currentScheduleResult);

// var_dump($currentScheduleResultData);
$oddsUrl = "https://api.sportsdata.io/v3/nfl/odds/json/GameOddsByWeek/" . $currentScheduleResultData[0]->ApiSeason . "/" . $currentScheduleResultData[0]->ApiWeek . "?key=" . $this->config['apiKeys']['nfl']['liveOdds'];
$oddsResult = \adzmvc\RESTApiHelper::getREST($oddsUrl);
$oddsData = \json_decode($oddsResult, true);

$teams = ['Home', 'Away'];

$fileSpread = fopen($fullPath . $fileNameSpread, "w");
$fileMoneyLine = fopen($fullPath . $fileNameMoneyLine, "w");
$fileTotal = fopen($fullPath . $fileNameTotal, "w");
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
fputcsv($fileSpread, $spreadHeader);

$moneyLineHeader = [
  'Week',
  'Date',
  'Bookmaker',
  'Sport',
  'Rotation',
  'Team',
  'MoneyLine',
  'Last Update'
];
fputcsv($fileMoneyLine, $moneyLineHeader);

$totalHeader = [
  'Week',
  'Date',
  'Bookmaker',
  'Sport',
  'Rotation',
  'Away',
  'Home',
  'Total',
  'Odds',
  'Last Update'
];
fputcsv($fileTotal, $totalHeader);

foreach ($oddsData as $game) {
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
        
        // MoneyLine
        $format = [
          'Week' => $game['Week'],
          'Date' => date_format($date,"m/d/Y"),
          'Bookmaker' => $book['Sportsbook'],
          'Sport' => 'nfl',
          'Rotation' => $game[$team . "RotationNumber"],
          'Team' => $game[$team . "TeamName"],
          'MoneyLine' => $book[$team . "MoneyLine"],
          'Last Updated' => (new DateTime('America/New_York'))->format('h:i:s')
        ];
        fputcsv($fileMoneyLine, $format);
      }

      // Total
      $format = [
        'Week' => $game['Week'],
        'Date' => date_format($date,"m/d/Y"),
        'Bookmaker' => $book['Sportsbook'],
        'Sport' => 'nfl',
        'Rotation' => $game[$team . "RotationNumber"],
        'Away' => $game["AwayTeamName"],
        'Home' => $game["HomeTeamName"],
        'Total' => $book[$team . "PointSpread"],
        'Odds' => $book[$team . "PointSpreadPayout"],
        'Last Updated' => (new DateTime('America/New_York'))->format('h:i:s')
      ];
      fputcsv($fileTotal, $format);
    }
  }
}

?>

<style>
  .slo-btn-csv {
    float: right;
    font-size: 16px;
    text-align: center;
    display: block;
    text-decoration: none !important;
    color: #fff !important;
    padding: 8px;
    background: #DD3333;
    width: 160px;
    margin: 10px 8px !important;
  }
</style>

<a class="slo-btn-csv" href="<?= $uploadsUrl . $fileNameSpread ?>">Spread CSV</a>
<a class="slo-btn-csv" href="<?= $uploadsUrl . $fileNameMoneyLine ?>">MoneyLine CSV</a>
<a class="slo-btn-csv" href="<?= $uploadsUrl . $fileNameTotal ?>">Total CSV</a>