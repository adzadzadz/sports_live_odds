(function() {

  jQuery(document).ready(() => {
    jQuery(".slo-dropdown").on('click', function(e) {
      toggleSloDropdown(this);
    });
    jQuery(document).mouseup(function(e) {
      var container = jQuery(".slo-dropdown");
    
      // if the target of the click isn't the container nor a descendant of the container
      if (!container.is(e.target) && container.has(e.target).length === 0) {
        container.find(".slo-dropdown-menu").addClass("slo-hidden");
      }
    });
    function toggleSloDropdown(elem) {
      let dropdownMenu = jQuery(elem).find(".slo-dropdown-menu");
      dropdownMenu.toggleClass("slo-hidden")
    }

  });

})();

moment.tz.add(["America/New_York|EST EDT EWT EPT|50 40 40 40|01010101010101010101010101010101010101010101010102301010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010|-261t0 1nX0 11B0 1nX0 11B0 1qL0 1a10 11z0 1qN0 WL0 1qN0 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1qN0 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1qN0 WL0 1qN0 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1qN0 WL0 1qN0 11z0 1o10 11z0 RB0 8x40 iv0 1o10 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1qN0 WL0 1qN0 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1o10 1fz0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1fz0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1fz0 1a10 1fz0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1fz0 1cN0 1cL0 1cN0 1cL0 s10 1Vz0 LB0 1BX0 1cN0 1fz0 1a10 1fz0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1fz0 1a10 1fz0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 14p0 1lb0 14p0 1nX0 11B0 1nX0 11B0 1nX0 14p0 1lb0 14p0 1lb0 14p0 1nX0 11B0 1nX0 11B0 1nX0 14p0 1lb0 14p0 1lb0 14p0 1lb0 14p0 1nX0 11B0 1nX0 11B0 1nX0 14p0 1lb0 14p0 1lb0 14p0 1nX0 11B0 1nX0 11B0 1nX0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0|21e6"]);
var sloCurrentStartDate = moment().tz("America/New_York").format("YYYY-MM-DD");

/**
 * Prefered bookies list: 'Pinnacle', '5Dimes', 'WestgateSuperbookNV', 'DraftKings', 'FanDuel'
 */
class SLO {
  sport = 'nfl';
  type  = 'MoneyLine';
  intervalId = null;
  resultData = null;
  sportsBooks = ['Pinnacle', 'WestgateSuperbookNV', 'DraftKings', 'FanDuel', 'SugarHousePA'];
  sloCurrentDateTime = null;

  testCheckUrlTime(url) {
    var today = new Date();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    jQuery(`#${this.sport}Data .fetchTime`).html(time);
    jQuery(`#${this.sport}Data .fetchUrl`).html(url);
  }

  fetchData(url, type) {
    console.log(url);
    this.request(url);

    if (this.intervalId !== null) {
      clearInterval(this.intervalId);
    }
    let slo = this;
    this.intervalId = setInterval(function(){
      slo.request(url);
    }, 120000); // 2mins
  }

  request(url) {
    let request = jQuery.ajax({
      url: url
    });

    request.done((data) => {
      this.sloCurrentDateTime = moment().tz("America/New_York").format("MM/DD - hh:mma");
      this.resultData = data;
      this.setSloOddsView(this.resultData, this.sport, this.type);
    });
  }

  setSloOddsView(data, sport, type = "MoneyLine") {
    // var sportsBooks = ['Pinnacle', 'WestgateSuperbookNV', 'DraftKings', 'FanDuel', 'SugarHousePA'];
    let contentContainer = jQuery(`section#${sport}ContentContainer`);
    contentContainer.html("");
    let sloTableHeader = `
      <section class="teams row">
        <div class="col-12">
          <div class="sloTimeStamp sloUpdatedAt sloGameDateTime">Updated at: ${this.sloCurrentDateTime} ET</div>
        </div>
        <div class="col-12 mimicTable">
          <section id="${sport}Content" class="slo-feed-content"></section>
        </div>
      </section>
    `;
    contentContainer.append(sloTableHeader);

    var content = jQuery(`section#${sport}Content`);

    content.html("");
    content.append(`
      <section id="${sport}LiveOddsSection">
        <div class="row">
          <div class="col-4 slo-team-area">
            <div class="cell col-12 slo-border-bottom slo-border-top slo-table-header">
              <div class="slo-vertical-center">
                Schedule
              </div>
            </div>
          </div>
          <div class="col-8 slo-line-area slo-allow-overflow">
            <div class="col-12">
              <div class="row bookie-row slo-border-bottom slo-border-top slo-table-header">
                <div class="cell slo-col-hack-5 slo-header-logo-container slo-table-header">
                  <img class="img-responsive slo-vertical-center" src="${sloData.pluginsUrl}/sports_live_odds/src/assets/imgs/odds-pinnacle-logo.png" alt="Pinnacle Logo">
                </div>
                <div class="cell slo-col-hack-5 slo-header-logo-container slo-table-header">
                  <img class="img-responsive slo-vertical-center" src="${sloData.pluginsUrl}/sports_live_odds/src/assets/imgs/odds-westgate-logo.png" alt="Westgate Logo">
                </div>
                <div class="cell slo-col-hack-5 slo-header-logo-container slo-table-header">
                  <img style="max-height: 45px;" class="img-responsive slo-vertical-center" src="${sloData.pluginsUrl}/sports_live_odds/src/assets/imgs/odds-draftkings-logo.png" alt="Westgate Logo">
                </div>
                <div class="cell slo-col-hack-5 slo-header-logo-container slo-table-header">
                  <img class="img-responsive slo-vertical-center" src="${sloData.pluginsUrl}/sports_live_odds/src/assets/imgs/odds-fanduel-logo.png" alt="Westgate Logo">
                </div>
                <div class="cell slo-col-hack-5 slo-header-logo-container slo-table-header">
                  <img class="img-responsive slo-vertical-center" src="${sloData.pluginsUrl}/sports_live_odds/src/assets/imgs/odds-sugarhouse-logo.png" alt="Westgate Logo">
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    `);

    data.forEach(i => {
      // TEMPORARY HACK
      if (sport == 'ncaaf') {
        if (i.GameId == 9393 || i.GameId == 9812)
          return false;
      }
      // END OF TEMPORARY HACK

      let books = {};
      i.PregameOdds.forEach(it => {
        if (this.sportsBooks.includes(it.Sportsbook)) {
          books[it.Sportsbook] = it;
        }
      });

      let appendSign = '';
      let payout = '-';
      let bookies = {
        Away : '',
        Home : ''
      };
      let teams = ['Away', 'Home'];
      let slo = this;
      teams.forEach( function(item, index) {
        slo.sportsBooks.forEach(book => {
          // Line
          if (type == "OverUnder") {
            let betTypeResult = books[book] ? books[book][type] : '-';
            let checkPositive = books[book] ? books[book][`${item}MoneyLine`] : 0;
            if (books[book]) {
              payout = checkPositive > 0 && books[book] ? books[book]['OverPayout'] : books[book]['OverPayout'];
            }
            appendSign = betTypeResult == "-" ? "" : "o" + betTypeResult;
          } else  {
            let betTypeResult = books[book] ? books[book][item + type] : '-';
            appendSign = betTypeResult > 0 ? "+" + betTypeResult : betTypeResult;          
          }
          // PointSpread Payout
          if (type == "PointSpread") { 
            payout = books[book] ? books[book][item + type + 'Payout'] : '-';
          }
          let appendPayout = type !== "MoneyLine" ? `<div> ${payout} </div>` : '';
          let appendLineVal = `<div> ${appendSign != null ? appendSign : '-'} </div>`;
          bookies[item] += `
            <div class="cell slo-col-hack-5">
              <div class="slo-val-box">
                <div class="slo-vertical-center">
                  ${appendLineVal}
                  ${appendPayout}
                </div>
              </div>
            </div>
          `;
          payout = '-';
        });
      });

      let htmlTeamName = `
        <div class="cell col-12 slo-border-top slo-team-name">
          <div class="sloGameDateTime">
            ${moment(i.DateTime).format("MM/DD, hh:mm A")} ET
          </div>
          <div class="slo-vertical-center">
            ${i.AwayTeamName}
          </div>
        </div>
        <div class="cell col-12 slo-border-bottom slo-team-name">
          <div class="slo-vertical-center">
            ${i.HomeTeamName}
          </div>
        </div>
      `;
      jQuery(`#${sport}LiveOddsSection .slo-team-area`).append(htmlTeamName);

      let htmlLineData = `
        <div class="col-12 slo-border-top">
          <div class="row bookie-row">
            <div class="col-12 sloTimeStamp"></div>
            ${bookies['Away']}
          </div>
        </div>
        <div class="col-12 slo-border-bottom">
          <div class="row bookie-row">
            ${bookies['Home']}
          </div>
        </div>
      `
      jQuery(`#${sport}LiveOddsSection .slo-line-area`).append(htmlLineData);
    });
  }

}