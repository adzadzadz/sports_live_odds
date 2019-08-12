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
      <div class="dropdown-toggle" href="#" role="button" id="typeDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Type
      </div>

      <div class="dropdown-menu" aria-labelledby="typeDropdown">
        <a class="oddsMlb dropdown-item" data-type="type" data-value="PointSpread" >SPREAD</a>
        <a class="oddsMlb dropdown-item" data-type="type" data-value="OverUnder" >TOTAL</a>
        <a class="oddsMlb dropdown-item" data-type="type" data-value="MoneyLine" >MONEYLINE</a>
      </div>
    </div>
    <div class="col-md-4">
      <strong>GAME</strong>
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

  // #1 Date Setup
  jQuery("#dateDisplay").text(getClosestDateFromList(new Date(currentStartDate)));

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
    var newDate = 0;
    if (dateElem.data('type') == "prev") {
      newDate = getClosestDateFromList(selected, - 1);
    }
    if (jQuery(this).data('type') == "next") {
      newDate = getClosestDateFromList(selected, 1);
    }
    dateDisplay.text(newDate);
    fetchData(
      "https://api.sportsdata.io/v3/mlb/odds/json/GameOddsByDate/" + newDate + "?key=<?= $this->config['apiKeys']['mlb']['liveOdds'] ?>"
    );
  });

  var selectedDate = jQuery("#dateDisplay").text();
  
  // #2 The actual request
  fetchData(
    "https://api.sportsdata.io/v3/mlb/odds/json/GameOddsByDate/" + selectedDate + "?key=<?= $this->config['apiKeys']['mlb']['liveOdds'] ?>"
  );

  function fetchData(url) {
    function request() {
      var request = jQuery.ajax({
        url: url
      });

      request.done((data) => {
        resultData = data;
        setGames(resultData);
      });
    }
    request();

    if (intervalId !== null) {
      clearInterval(intervalId);
    }
    var intervalId = setInterval(function(){
      request();
    }, 120000); // 2mins
  }
   
  // #3 Type Setup
  var type = "MoneyLine";
  jQuery("#typeDropdown").html(type.toUpperCase());
  jQuery(".oddsMlb.dropdown-item").on("click", function(e) {
    if ( jQuery(this).data("type") == "type" ) {
      type = jQuery(this).data("value");
      jQuery("#typeDropdown").html(type.toUpperCase());
    }    
    setGames(resultData);
  });
  
  var sportsBooks = ['Pinnacle', 'WestgateSuperbookNV', 'DraftKings', 'FanDuel', 'SugarHousePA'];
  
  function setGames(data) {
    var container = jQuery("section#content");
    container.html("");
    data.forEach(i => {
      let gameId = i.GameId;
      console.log(gameId)
      let books = {};
      i.PregameOdds.forEach(it => {
        if (sportsBooks.includes(it.Sportsbook)) {
          books[it.Sportsbook] = it;
        }
      });
      
      let booksAway = '';
      let booksHome = '';
      let appendSign = '';
      let payout = '-';
      /**
       * Away Team
       */
      sportsBooks.forEach(book => {
        // Line
        if (type == "OverUnder") {
          let betTypeResult = books[book] ? books[book][type] : '-';
          let checkPositive = books[book] ? books[book]['AwayMoneyLine'] : 0;
          if (books[book]) {
            payout = checkPositive > 0 && books[book] ? books[book]['OverPayout'] : books[book]['OverPayout'];
          }
          appendSign = betTypeResult == "-" ? "" : "o" + betTypeResult;
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

      /**
       * Home Team
       */
      sportsBooks.forEach(book => {
        if (type == "OverUnder") {
          let betTypeResult = books[book] ? books[book][type] : '-';
          let checkPositive = books[book] ? books[book]['HomeMoneyLine'] : '-';
          if (books[book]) {
            payout = checkPositive > 0 && books[book] ? books[book]['UnderPayout'] : books[book]['UnderPayout'];
          }
          appendSign = betTypeResult == "-" ? "" : "u" + betTypeResult;
        } else {
          let betTypeResult = books[book] ? books[book]['Home' + type] : '-';
          appendSign = betTypeResult > 0 ? "+" + betTypeResult : betTypeResult;
        }
         // PointSpread Payout
        if (type == "PointSpread") { 
          payout = books[book] ? books[book]['Home' + type + 'Payout'] : '-';
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