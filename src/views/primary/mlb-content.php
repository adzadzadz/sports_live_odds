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
    <div class="col-md-4">
      <div class="row">
        <button id="datePrev" class="dateChanger btn btn-sm btn-success col-md-3" data-type="prev">PREV</button>
        <div id="dateDisplay" class="col-md-6" style="font-size: .8em; padding-top: 8px;">2019-05-03</div>
        <button id="dateNext" class="dateChanger btn btn-sm btn-success col-md-3" data-type="next">NEXT</button>
      </div>
    </div>
    <div class="dropdown col-md-4">
      <div class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Type
      </div>

      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <a class="oddsMlb dropdown-item" data-type="type" data-value="PointSpread" >SPREAD</a>
        <a class="oddsMlb dropdown-item" data-type="type" data-value="OverUnder" >TOTAL</a>
        <a class="oddsMlb dropdown-item" data-type="type" data-value="MoneyLine" >MONEYLINE</a>
      </div>
    </div>
    <div class="dropdown col-md-4">
      <div class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Duration
      </div>

      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <a class="oddsMlb dropdown-item" data-type="duration" data-value="game" >GAME</a>
        <a class="oddsMlb dropdown-item" data-type="duration" data-value="f5" >F5</a>
        <a class="oddsMlb dropdown-item" data-type="duration" data-value="live" >LIVE</a>
      </div>
    </div>
  </section>
</header>
<div class="content col-md-12">
  <section class="teams row">
    <div class="col-md-12 mimicTable">
      <div id="tableHeader" class="row">
        <div class="cell col-md-4">Schedule</div>
        <div class="col-md-8">
          <div class="row">
            <div class="cell col-md-2">Open</div>
            <div class="cell col-md-2">Westgate</div>
            <div class="cell col-md-2">Caesars</div>
            <div class="cell col-md-2">Pinnacle</div>
            <div class="cell col-md-2">5Dimes</div>
            <div class="cell col-md-2">BetOnline</div>
          </div>
        </div>
      </div>
      <section id="content"></section>
    </div>
  </section>
</div>

<script>
(function() {

  var resultData = null;
  var intervalId = null;

  // Config
  var selectedDate = jQuery("#dateDisplay").text();

  // #1 Date Setup
  jQuery("#dateDisplay").text(getClosestDateFromList(new Date));

  function getClosestDateFromList(selectedDate, addDays = 0) {
    var dateList = <?= $dateList ?>;
    if (addDays !== 0) {
      selectedDate.setDate( selectedDate.getDate() + addDays );
    }
    let closest = null;

    dateList.forEach(function(d) {
      const date = new Date(d);

      if (date <= selectedDate && (date > new Date(closest) || date > closest)) {
          closest = d;
      }
    });
    return closest;
  }

  jQuery(".dateChanger").on('click', function(e) {
    let dateElem = jQuery(this);
    let dateDisplay = jQuery('#dateDisplay');
    let selected = new Date(dateDisplay.text())
    var newVal = 0;
    if (dateElem.data('type') == "prev") {
      newVal = getClosestDateFromList(selected, - 1);
    }
    if (jQuery(this).data('type') == "next") {
      newVal = getClosestDateFromList(selected, 1);
    }
    dateDisplay.text(newVal);
    fetchData(
      "https://api.sportsdata.io/v3/mlb/odds/json/GameOddsByDate/" + newVal + "?key=<?= $this->config['apiKeys']['mlb']['liveOdds'] ?>"
    );
  });

  // #2 The actual request
  fetchData(
    "https://api.sportsdata.io/v3/mlb/odds/json/GameOddsByDate/" + selectedDate + "?key=<?= $this->config['apiKeys']['mlb']['liveOdds'] ?>"
  );

  jQuery(".oddsMlb.dropdown-item").on('click', function(e) {
    if ( jQuery(this).data("type") == "duration" ) {
      const duration = jQuery(this).data("value");
      if (duration == "live") {
        fetchData( "https://api.sportsdata.io/v3/mlb/odds/json/LiveGameOddsByDate/" + selectedDate + "?key=<?= $this->config['apiKeys']['mlb']['liveOdds'] ?>");
      } else if(duration == "game") {
        fetchData( "https://api.sportsdata.io/v3/mlb/odds/json/GameOddsByDate/" + selectedDate + "?key=<?= $this->config['apiKeys']['mlb']['liveOdds'] ?>" );
      }
    }
  });

  function fetchData(url) {
    if (intervalId !== null) {
      clearInterval(feedInterval);
    }
    var feedInterval = setInterval(function(){
      var request = jQuery.ajax({
        url: url
      });

      request.done((data) => {
        resultData = data;
        setGames(resultData);
      });
    }, 5000);
  }
   
  // #3 Type Setup
  var type = "MoneyLine";
  jQuery(".oddsMlb.dropdown-item").on("click", function(e) {
    if ( jQuery(this).data("type") == "type" ) {
      type = jQuery(this).data("value");
    }    
    setGames(resultData);
  });
  
  var sportsBooks = ['Pinnacle', 'WestgateSuperbookNV', 'DraftKings', 'FanDuel', 'SugarHousePA'];
  
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
      var booksHome = '';
      let appendSign = null;
      let payout = '-';
      sportsBooks.forEach(book => {
        // Line
        if (type == "OverUnder") {
          let betTypeResult = books[book] ? books[book][type] : '-';
          let checkPositive = books[book] ? books[book]['AwayMoneyLine'] : 0;
          if (books[book]) {
            payout = checkPositive > 0 && books[book] ? books[book]['UnderPayout'] : books[book]['UnderPayout'];
          } else {
            payout = '-';
          }
          appendSign = checkPositive > 0 ? "u" + betTypeResult : "o" + betTypeResult;
        } else  {
          let betTypeResult = books[book] ? books[book]['Away' + type] : '-';
          appendSign = betTypeResult > 0 ? "+" + betTypeResult : betTypeResult;          
        }
        // PointSpread Payout
        if (type == "PointSpread") { 
          payout = books[book] ? books[book]['Away' + type + 'Payout'] : '-';
        }
        let appendPayout = type !== "MoneyLine" ? '<div>' + payout + '</div>' : '';
        booksAway += '<div class="cell col-md-2">' + 
          '<div>' + appendSign + '</div>' +
          appendPayout +
        '</div>';
      });

      sportsBooks.forEach(book => {
        if (type == "OverUnder") {
          let betTypeResult = books[book] ? books[book][type] : '-';
          let checkPositive = books[book] ? books[book]['HomeMoneyLine'] : '-';
          if (books[book]) {
            payout = checkPositive > 0 && books[book] ? books[book]['UnderPayout'] : books[book]['UnderPayout'];
          } else {
            payout = '-';
          }
          appendSign = checkPositive > 0 ? "u" + betTypeResult : "o" + betTypeResult;
          appendSign = checkPositive > 0 ? "u" + betTypeResult : "o" + betTypeResult;
        } else {
          let betTypeResult = books[book] ? books[book]['Home' + type] : '-';
          appendSign = betTypeResult > 0 ? "+" + betTypeResult : betTypeResult;
        }
         // PointSpread Payout
        if (type == "PointSpread") { 
          payout = books[book] ? books[book]['Away' + type + 'Payout'] : '-';
        }
        let appendPayout = type !== "MoneyLine" ? '<div>' + payout + '</div>' : '';
        booksHome += '<div class="cell col-md-2">' + 
          '<div>' + appendSign + '</div>' +
          appendPayout +
        '</div>';
      });
      container.append(
        '<div class="row">' +
          '<div class="cell col-md-4">' + 
            i.AwayTeamName +
          '</div>' +
          '<div class="col-md-8">' +
            '<div class="row">' +
              '<div class="cell col-md-2">Open</div>' +
              booksAway +
            '</div>' +
          '</div>' +
        '</div>' +
      '</div>');
      container.append(
        '<div class="row">' +
          '<div class="cell col-md-4">' + 
            i.HomeTeamName +
          '</div>' +
          '<div class="col-md-8">' +
            '<div class="row">' +
              '<div class="cell col-md-2">Open</div>' +
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