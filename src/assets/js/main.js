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
  typeText = 'MONEYLINE';
  intervalId = null;
  resultData = null;
  // sportsBooks = ['Pinnacle', 'WestgateSuperbookNV', 'DraftKings', 'FanDuel', 'SugarHousePA'];
  sportsBooks = ['Pinnacle', 'Bookmaker', 'DraftKings', 'FanDuel', 'SugarHousePA'];
  sloCurrentDateTime = null;

  testCheckUrlTime(url) {
    var today = new Date();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    jQuery(`#${this.sport}Data .fetchTime`).html(time);
    jQuery(`#${this.sport}Data .fetchUrl`).html(url);
  }

  fetchData(url, type) {
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
        <div class="col-12 sloTimeStamp">
          <div class="sloUpdatedAt sloGameDateTime">Updated at: ${this.sloCurrentDateTime} ET</div>
        </div>
        <div class="col-12 mimicTable">
          <section id="${sport}Content" class="slo-feed-content"></section>
        </div>
      </section>
    `;
    contentContainer.append(sloTableHeader);

    var content = jQuery(`section#${sport}Content`);

    content.html("");

    let sportLogoSwitch;
    if (sport == 'mlb') {
      sportLogoSwitch = `<img class="img-responsive slo-vertical-center" src="${sloData.pluginsUrl}/sports_live_odds/src/assets/imgs/odds-westgate-logo-v2.png" alt="Bookmaker Logo" style="padding-left: 16px;">`;
    } else {
      sportLogoSwitch = `<img class="img-responsive slo-vertical-center" src="${sloData.pluginsUrl}/sports_live_odds/src/assets/imgs/odds-bookmaker-logo-v3.jpg" alt="Bookmaker Logo" style="padding-left: 0px; max-height: 60px;">`;
    }
    
    content.append(`
      <section id="${sport}LiveOddsSection">
        <div class="row">
          <div class="col-md-4 col-5 slo-team-area">
            <div class="cell col-12 slo-border-bottom slo-border-top slo-table-header">
              <div class="slo-vertical-center">
                Schedule
              </div>
            </div>
          </div>
          <div class="col-md-8 col-7 slo-line-area slo-allow-overflow">
            <div class="col-12">
              <div class="row bookie-row slo-border-bottom slo-border-top slo-table-header">
                <div class="cell slo-col-hack-5 slo-header-logo-container slo-table-header">
                  <img class="img-responsive slo-vertical-center" src="${sloData.pluginsUrl}/sports_live_odds/src/assets/imgs/odds-pinnacle-logo.png" alt="Pinnacle Logo">
                </div>
                <div class="cell slo-col-hack-5 slo-header-logo-container slo-table-header">
                  ${sportLogoSwitch}
                </div>
                <div class="cell slo-col-hack-5 slo-header-logo-container slo-table-header">
                  <img style="max-height: 45px;" class="img-responsive slo-vertical-center" src="${sloData.pluginsUrl}/sports_live_odds/src/assets/imgs/odds-draftkings-logo.png" alt="Westgate Logo">
                </div>
                <div class="cell slo-col-hack-5 slo-header-logo-container slo-table-header">
                  <img class="img-responsive slo-vertical-center" src="${sloData.pluginsUrl}/sports_live_odds/src/assets/imgs/odds-fanduel-logo.png" alt="Westgate Logo">
                </div>
                <div class="cell slo-col-hack-5 slo-header-logo-container slo-table-header">
                  <img class="img-responsive slo-vertical-center" style="height: 50px;" src="${sloData.pluginsUrl}/sports_live_odds/src/assets/imgs/odds-sugarhouse-logo-v2.png" alt="Westgate Logo">
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    `);

    data.forEach(game => {
      let books = {};
      game.PregameOdds.forEach(it => {
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

      var queryType;
      let booksVals = {
        'Away' : [],
        'Home' : []
      };
      teams.forEach((team) => {
        slo.sportsBooks.forEach((book) => {
          if (books[book]) {
            switch (type) {
              case 'MoneyLine':
                queryType = `MoneyLine`;
                setVals(`${team}${queryType}`);
                break;
              case 'PointSpread':
                queryType = `${type}Payout`;      
                setVals(`${team}${queryType}`);
                break;
              case 'OverUnder':
                if (team == 'Away') {
                  queryType = `OverPayout`;
                  setVals(queryType);
                } else if (team == 'Home') {
                  queryType = `UnderPayout`;
                  setVals(queryType);
                }
                break;
              default:
                break;
            }
            
            function setVals(queryVal) {
              if (queryVal) {
                booksVals[`${team}`].push( parseFloat(books[book][queryVal]) );
              }
            }

          }
        });
      });
      
      let bestLine = {
        Home : undefined,
        Away : undefined
      };

      if (booksVals.Home.length > 0) {
        bestLine.Home = Math.max.apply(Math, booksVals.Home);
      }      
      if (booksVals.Away.length > 0) {
        bestLine.Away = Math.max.apply(Math, booksVals.Away);
      }
      
      teams.forEach((team, index) => {
        slo.sportsBooks.forEach(book => {
          let isBestLine = false;
          if (books[book]) {
            if (type == 'OverUnder') {
              if (team == 'Away') {
                if (bestLine[team] == books[book][`OverPayout`]) {
                  isBestLine = true;
                }
              } else if (team =='Home') {
                if (bestLine[team] == books[book][`UnderPayout`]) {
                  isBestLine = true;
                }
              }
            } else if (bestLine[team] == books[book][`${team}${queryType}`]) {
              isBestLine = true;
            }
            
          }

          // Line
          if (type == "OverUnder") {
            let ouSign;
            let payoutType;
            let betTypeResult = books[book] && books[book][type] != null ? books[book][type] : '-';
            if (team == 'Away') {
              ouSign = "o";
              payoutType = 'OverPayout';
            } else {
              ouSign = 'u';
              payoutType = 'UnderPayout';
            }
            if (books[book]) {
              payout = books[book][team + type + 'Payout'] != null ? books[book][payoutType] : '-';
            }
            appendSign = betTypeResult == "-" ? "-" : ouSign + betTypeResult;
          } else  {
            let betTypeResult = books[book] ? books[book][team + type] : '-';
            appendSign = betTypeResult > 0 ? "+" + betTypeResult : betTypeResult;          
          }

          // PointSpread Payout
          if (type == "PointSpread") { 
            payout = books[book] && books[book][team + type + 'Payout'] != null ? books[book][team + type + 'Payout'] : '-';
          }
          let appendPayout = type !== "MoneyLine" ? `<div> ${payout} </div>` : '';
          let appendLineVal = `<div> ${appendSign != null ? appendSign : '-'} </div>`;
          
          bookies[team] += `
            <div class="cell slo-col-hack-5 slo-cell-line-data">
              <div class="slo-val-box ${isBestLine ? 'slo-val-box-best' : ''}">
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

      let homeLogo = `<img src="${sloData.pluginsUrl}/sports_live_odds/src/assets/imgs/teams/${sport}/${game.HomeTeamName}.png" alt="" class="img-responsive slo-team-logo">`;
      let awayLogo = `<img src="${sloData.pluginsUrl}/sports_live_odds/src/assets/imgs/teams/${sport}/${game.AwayTeamName}.png" alt="" class="img-responsive slo-team-logo">`;
      let htmlTeamName = `
        <div class="cell col-12 slo-border-top slo-team-name">
          <div class="sloGameDateTime">
            ${moment(game.DateTime).format("MM/DD, hh:mm A")} ET
          </div>
          <div class="slo-vertical-center">
            <span class="slo-texas-rotation-number">
              ${game.AwayRotationNumber ? game.AwayRotationNumber : ''}
            </span>
            ${sport !== 'ncaaf' ? awayLogo : ''}
            ${game.AwayTeamName}
          </div>
        </div>
        <div class="cell col-12 slo-border-bottom slo-team-name">
          <div class="slo-vertical-center">
            <span class="slo-texas-rotation-number">
              ${game.HomeRotationNumber ? game.HomeRotationNumber : ''}
            </span> 
            ${sport !== 'ncaaf' ? homeLogo : ''}
            ${game.HomeTeamName}
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