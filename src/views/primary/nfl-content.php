<?php 

  
?>

<section id="nflData" class="sportData">

  <header class="col-md-12">
    <section class="filter row">
    <div class="dropdown col-md-4">
        <div class="dropdown-toggle" href="#" role="button" id="typeDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Type
        </div>

        <div id="gameTypeSelection" class="dropdown-menu" aria-labelledby="typeDropdown">
          
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

</section>


<script>
(function() {
  var nflGameTypes = [
    'HOF', 'PRE1', 'PRE2', 'PRE3', 'PRE4', 'WEEK1', 'WEEK2', 'WEEK3', 'WEEK4', 'WEEK5', 
    'WEEK6', 'WEEK7', 'WEEK8', 'WEEK9', 'WEEK10', 'WEEK11', 'WEEK12', 'WEEK13', 'WEEK14', 'WEEK15', 
    'WEEK16', 'WEEK17', 'WILD CARD', 'DIVISION ROUND', 'CONF CHAMP', 'PRO BOWL', 'SUPER BOWL'
  ];
  var resultData = null;
  var intervalId = null;
  var gameType = null;
  // #1 Game Type Setup
  if (gameType == null) {}

  switch (gameType) {
    case "HDF":
      
      break;
  
    default:
      break;
  }

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
   
  // #3 Book Type Setup
  var type = "MoneyLine";
  jQuery("#nflData #typeDropdown").html(type.toUpperCase());
  jQuery("#nflData .oddsMlb.dropdown-item").on("click", function(e) {
    if ( jQuery(this).data("type") == "type" ) {
      type = jQuery(this).data("value");
      jQuery("#nflData #typeDropdown").html(type.toUpperCase());
    }    
    setGames(resultData);
  });
  
  var sportsBooks = ['Pinnacle', 'WestgateSuperbookNV', 'DraftKings', 'FanDuel', 'SugarHousePA'];
  
  function setGames(data) {
    var container = jQuery("#nflData section#content");
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