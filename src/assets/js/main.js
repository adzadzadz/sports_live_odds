(function() {

  jQuery(document).ready(() => {
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

  });

})();

function setSloOddsView(data, sport, type = "MoneyLine") {
  var sportsBooks = ['Pinnacle', 'WestgateSuperbookNV', 'DraftKings', 'FanDuel', 'SugarHousePA'];
  var container = jQuery(`section#${sport}Content`);

  container.html("");
  data.forEach(i => {
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
      let appendPayout = type !== "MoneyLine" ? `<div> ${payout} </div>` : '';
      booksAway += `
        <div class="cell slo-col-hack-5">
          <div> ${appendSign} </div>
          ${appendPayout}
        </div>
      `;
      payout = '-';
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
      let appendPayout = type !== "MoneyLine" ? `<div> ${payout} </div>` : '';
      booksHome += `
        <div class="cell slo-col-hack-5">
          <div> ${appendSign} </div>
          ${appendPayout}
        </div>
      `;
      payout = '-';
    });
    
    var html = `
      <section class="slo-dropshadow">

        <div class="row">
          <div class="cell col-md-4">
            ${i.AwayTeamName}
          </div>
          <div class="col-md-8">
            <div class="row">
              ${booksAway}
            </div>
          </div>
        </div>

        <div class="row">
          <div class="cell col-md-4">
            ${i.HomeTeamName}
          </div>
          <div class="col-md-8">
            <div class="row">
              ${booksHome}
            </div>
          </div>
        </div>
        
      </section>
      <div class='slo-spacer'></div>
    `;
    container.append(html);
  });
}    