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



<section id="mlbData" class="sportData">
  <header class="col-md-12">
    <section class="filter row slo-dropshadow slo-filter-wrap">
      <div class="col-md-4">
        <div class="row">
          <button id="datePrev" class="dateChanger btn btn-sm btn-success col-md-3" data-type="prev">PREV</button>
          <div id="dateDisplay" class="col-md-6" style="font-size: .8em; padding-top: 8px;">2019-05-03</div>
          <button id="dateNext" class="dateChanger btn btn-sm btn-success col-md-3" data-type="next">NEXT</button>
        </div>
      </div>
      <div class="slo-dropdown col-md-4">
        <div class="slo-dropdown-toggle" href="#" role="button" id="typeDropdown">
          Type
        </div>

        <div class="slo-dropdown-menu slo-hidden">
          <div class="oddsMlb slo-dropdown-item" data-type="type" data-value="PointSpread" >SPREAD</div>
          <div class="oddsMlb slo-dropdown-item" data-type="type" data-value="OverUnder" >TOTAL</div>
          <div class="oddsMlb slo-dropdown-item" data-type="type" data-value="MoneyLine" >MONEYLINE</div>
        </div>
      </div>
      <div class="col-md-4">
        <strong>GAME</strong>
      </div>
    </section>
  </header>
  <div class="slo-spacer"></div>
  <div class="content">
    <div class="col-md-12">
      <section class="teams row">
        <div class="col-md-12 mimicTable">
          <div id="tableHeader" class="row slo-dropshadow">
            <div class="cell col-md-4">Schedule</div>
            <div class="col-md-8">
              <div class="slo-row">
                <div class="cell slo-col-hack-5">Westgate</div>
                <div class="cell slo-col-hack-5">Caesars</div>
                <div class="cell slo-col-hack-5">Pinnacle</div>
                <div class="cell slo-col-hack-5">5Dimes</div>
                <div class="cell slo-col-hack-5">BetOnline</div>
              </div>
            </div>
          </div>
          <div class="slo-spacer"></div>
          <section id="content"></section>
        </div>
      </section>
    </div>
  </div>
</section>
<script>
  jQuery(".slo-dropdown").on('click', function(e) {
    toggleSloDropdown(this);
  });
  jQuery(document).mouseup(function(e) {
    var container = jQuery(".slo-dropdown");

    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) 
    {
      container.find(".slo-dropdown-menu").addClass("slo-hidden");
    }
  });
  function toggleSloDropdown(elem) {
    let dropdownMenu = jQuery(elem).find(".slo-dropdown-menu");
    dropdownMenu.toggleClass("slo-hidden")
  }
</script>
<script src="<?= plugins_url('../../assets/node_modules/moment/moment.js',__FILE__) ?>"></script>
<script src="<?= plugins_url('../../assets/node_modules/moment-timezone/moment-timezone.js',__FILE__) ?>"></script>
<script>
  moment.tz.add(["America/New_York|EST EDT EWT EPT|50 40 40 40|01010101010101010101010101010101010101010101010102301010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010|-261t0 1nX0 11B0 1nX0 11B0 1qL0 1a10 11z0 1qN0 WL0 1qN0 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1qN0 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1qN0 WL0 1qN0 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1qN0 WL0 1qN0 11z0 1o10 11z0 RB0 8x40 iv0 1o10 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1qN0 WL0 1qN0 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1o10 1fz0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1fz0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1fz0 1a10 1fz0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1fz0 1cN0 1cL0 1cN0 1cL0 s10 1Vz0 LB0 1BX0 1cN0 1fz0 1a10 1fz0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1fz0 1a10 1fz0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 14p0 1lb0 14p0 1nX0 11B0 1nX0 11B0 1nX0 14p0 1lb0 14p0 1lb0 14p0 1nX0 11B0 1nX0 11B0 1nX0 14p0 1lb0 14p0 1lb0 14p0 1lb0 14p0 1nX0 11B0 1nX0 11B0 1nX0 14p0 1lb0 14p0 1lb0 14p0 1nX0 11B0 1nX0 11B0 1nX0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0|21e6"]);
  var currentStartDate = moment().tz("America/New_York").format("YYYY-MM-DD");
</script>
<script>
(function() {
  var resultData = null;
  var intervalId = null;

  // #1 Date Setup
  jQuery("#mlbData #dateDisplay").text(getClosestDateFromList(new Date(currentStartDate)));

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

  var selectedDate = jQuery("#mlbData #dateDisplay").text();
  
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
  jQuery("#mlbData #typeDropdown").html(type.toUpperCase());
  jQuery(".oddsMlb.slo-dropdown-item").on("click", function(e) {
    if ( jQuery(this).data("type") == "type" ) {
      type = jQuery(this).data("value");
      text = jQuery(this).html();
      jQuery("#mlbData #typeDropdown").html(text.toUpperCase());
    }    
    setGames(resultData);
  });
  
  var sportsBooks = ['Pinnacle', 'WestgateSuperbookNV', 'DraftKings', 'FanDuel', 'SugarHousePA'];
  
  function setGames(data) {
    var container = jQuery("section#content");
    container.html("");
    data.forEach(i => {
      let gameId = i.GameId;
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
        booksAway += '<div class="cell slo-col-hack-5">' + 
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
        booksHome += '<div class="cell slo-col-hack-5">' + 
          '<div>' + appendSign + '</div>' +
          appendPayout +
        '</div>';
      });
      
      contentData =  '<section class="slo-dropshadow col-md-12" style="padding: 0 -15px;">';
      contentData += '<div class="row">';
      contentData += '<div class="cell col-md-4">'; 
      contentData += i.AwayTeamName;
      contentData += '</div>';
      contentData += '<div class="col-md-8">';
      contentData += '<div class="row">';
      contentData += booksAway;
      contentData += '</div>';
      contentData += '</div>';
      contentData += '</div>';

      contentData += '<div class="row">';
      contentData += '<div class="cell col-md-4">'; 
      contentData += i.HomeTeamName;
      contentData += '</div>';
      contentData += '<div class="col-md-8">';
      contentData += '<div class="row">';
      contentData += booksHome;
      contentData += '</div>';
      contentData += '</div>';
      contentData += '</div>';
      contentData += '</section>';

      container.append(contentData);
      container.append("<div class='slo-spacer'></div>");
    });
  }
})();
</script>