<?php 

  global $wpdb;
  $availableDates = [];
  $sportTable = \src\models\Sport::getTable();
  $scheduleTable = \src\models\SportSchedule::getTable();
  $sportResult = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $sportTable WHERE sport_code = %s", ['MLB']) );
  if (!$sportResult) {
    echo "Sport Code not found. Contact Dev.";
  } else {
    $availableDates = $wpdb->get_results( $wpdb->prepare("SELECT * from $scheduleTable where sport_id = %d", [$sportResult[0]->id]) );
  }
  
  $dateList = [];
  foreach ($availableDates as $date) { 
    $dateList[] = $date->date;
  }
  $dateList = json_encode($dateList);
?>


<style>
  section.teams .mimicTable div.cell{
    border: 1px grey solid;
    font-size: 0.6em;
    color: #757575;
  }
</style>

<header class="col-md-12">
  <section class="filter row">
    <div class="col-md-3">
      <div class="row">
        <button id="datePrev" class="btn btn-sm btn-success col-md-3">PREV</button>
        <div id="dateDisplay" class="col-md-6" style="font-size: .8em; padding-top: 8px;">2019-05-03</div>
        <button id="dateNext" class="btn btn-sm btn-success col-md-3">NEXT</button>
      </div>
    </div>
    <div class="dropdown col-md-3">
      <div class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Type
      </div>

      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <a class="dropdown-item" data-type="type" data-value="PointSpread" >SPREAD</a>
        <a class="dropdown-item" data-type="type" data-value="" >TOTAL</a>
        <a class="dropdown-item" data-type="type" data-value="MoneyLine" >MONEYLINE</a>
      </div>
    </div>
    <div class="dropdown col-md-5">
      <div class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Duration
      </div>

      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <a class="dropdown-item" data-value="" >Action</a>
        <a class="dropdown-item" data-value="" >Another action</a>
        <a class="dropdown-item" data-value="" >Something else here</a>
      </div>
    </div>
  </section>
</header>
<div class="content col-md-12">
  <section class="teams row">
    <div class="col-md-12 mimicTable">
    <div id="tableHeader" class="row">
        <div class="cell col-md-3">Schedule</div>
        <div class="col-md-3">
          <div class="row">
            <div class="cell col-md-6">Open</div>
            <div class="cell col-md-6">Consensus</div>      
          </div>
        </div>
        <div class="col-md-6">
          <div class="row">
            <div class="cell col-md-2">Westgate</div>
            <div class="cell col-md-2">Caesars</div>
            <div class="cell col-md-2">Pinnacle</div>
            <div class="cell col-md-2">5Dimes</div>
            <div class="cell col-md-2">BetOnline</div>
          </div>
        </div>
      </div>
      <section id="content"></section>
  </section>
</div>

<script>
(function() {

  var resultData = null;

  // #1 Date Setup
  var dateList = <?= $dateList ?>;
  const now = new Date();
  let closest = null;

  dateList.forEach(function(d) {
    const date = new Date(d);

    if (date <= now && (date > new Date(closest) || date > closest)) {
        closest = d;
    }
  });

  jQuery("#dateDisplay").text(closest);

  // #2 The actual request
  var selectedDate = jQuery("#dateDisplay").text();
  var request = jQuery.ajax({
    url: "https://api.sportsdata.io/v3/mlb/odds/json/GameOddsByDate/" + selectedDate + "?key=<?= $this->config['apiKeys']['mlb']['liveOdds'] ?>"
  });
  
  // #3 Type Setup
  var type = "MoneyLine";
  jQuery(".dropdown-item").on("click", function(e) {
    type = jQuery(this).data("value");
    setGames(resultData);
  });
  
  var sportsBooks = ['Pinnacle', 'WestgateSuperbookNV', 'DraftKings', 'FanDuel', 'SugarHousePA'];
  
  request.done((data) => {
   resultData = data;
   setGames(resultData);
  });

  function setGames(data) {
    var container = jQuery("section#content");
    container.html("");
    data.forEach(i => {
      let books = {};
      i.PregameOdds.forEach(it => {
        if (sportsBooks.includes(it.Sportsbook)) {
          books[it.Sportsbook] = it;
        }
      });
      
      var booksAway = '';
      sportsBooks.forEach(book => {
        let betTypeResult = books[book] ? books[book]['Away' + type] : '-';
        let appendSign  = betTypeResult > 0 ? "+" + betTypeResult : betTypeResult;
        booksAway += '<div class="cell col-md-2">' + appendSign + '</div>';
      });
      var booksHome = '';
      sportsBooks.forEach(book => {
        let betTypeResult = books[book] ? books[book]['Home' + type] : '-';
        let appendSign  = betTypeResult > 0 ? "+" + betTypeResult : betTypeResult;
        booksHome += '<div class="cell col-md-2">' + appendSign + '</div>';
      });
      container.append('<div class="row">' +
        '<div class="cell col-md-3">' + 
          i.AwayTeamName +
        '</div>' +
        '<div class="col-md-3">' +
          '<div class="row">' +
            '<div class="cell col-md-6">Open</div>' +
            '<div class="cell col-md-6">Consensus</div>' +
          '</div>' +
        '</div>' +
        '<div class="col-md-6">' +
          '<div class="row">' +
            booksAway +
          '</div>' +
        '</div>' +
      '</div>' +
    '</div>');
    container.append('<div class="row">' +
        '<div class="cell col-md-3">' + 
          i.HomeTeamName +
        '</div>' +
        '<div class="col-md-3">' +
          '<div class="row">' +
            '<div class="cell col-md-6">Open</div>' +
            '<div class="cell col-md-6">Consensus</div>' +
          '</div>' +
        '</div>' +
        '<div class="col-md-6">' +
          '<div class="row">' +
            booksHome +
          '</div>' +
        '</div>' +
      '</div>' +
    '</div>');
    container.append("<div style='margin-bottom: 25px;'></div>");
    });
  }
})();
</script>