<section id="nflData" class="sloSportData">
  <div class="col-md-12">
    <div class="fetchTime"></div>
    <div class="fetchUrl"></div>
  </div>
  <header class="col-md-8">
    <section class="filter row slo-dropshadow slo-filter-wrap">
      <div id="nflGameWeek" class="slo-dropdown col-md-6">
        <div class="slo-dropdown-toggle filter-label" href="#" role="button" id="nflWeekDropdown">
          <span id="nflWeekText">Week</span> <i class="fa fa-chevron-down"></i>
        </div>

        <div class="slo-dropdown-menu slo-hidden"></div>
      </div>
      <div class="slo-dropdown col-md-6">
        <div class="slo-dropdown-toggle filter-label" href="#" role="button" id="nflTypeDropdown">
        <span id="nflTypeText" data-type="type">Type</span> <i class="fa fa-chevron-down"></i>
        </div>

        <div class="slo-dropdown-menu slo-hidden">
          <div class="oddsNfl slo-dropdown-item" data-type="type" data-value="PointSpread" >SPREAD</div>
          <div class="oddsNfl slo-dropdown-item" data-type="type" data-value="OverUnder" >TOTAL</div>
          <div class="oddsNfl slo-dropdown-item" data-type="type" data-value="MoneyLine" >MONEYLINE</div>
        </div>
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
                <div class="cell slo-col-hack-5">Pinnacle</div>
                <div class="cell slo-col-hack-5">Westgate</div>
                <div class="cell slo-col-hack-5">DraftKings</div>
                <div class="cell slo-col-hack-5">FanDuel</div>
                <div class="cell slo-col-hack-5">SugarHouse</div>
              </div>
            </div>
          </div>
          <div class="slo-spacer"></div>
          <section id="nflContent"></section>
        </div>
      </section>
    </div>
  </div>
</section>

<script>

class NFL extends SLO{

  season = null;
  currentWeek = null;
  nflGameWeeks = {
    'HOF'   : 0, 
    'PRE1'  : 1,
    'PRE2'  : 2,
    'PRE3'  : 3,
    'PRE4'  : 4, 
    'WEEK1' : 1, 
    'WEEK2' : 2,
    'WEEK3' : 3,
    'WEEK4' : 4,
    'WEEK5' : 5, 
    'WEEK6' : 6,
    'WEEK7' : 7,
    'WEEK8' : 8,
    'WEEK9' : 9, 
    'WEEK10': 10,
    'WEEK11': 11,
    'WEEK12': 12,
    'WEEK13': 13,
    'WEEK14': 14,
    'WEEK15': 15, 
    'WEEK16': 26,
    'WEEK17': 17,
    'WILD CARD': 22, // No worries
    'DIVISION ROUND': 23, // No worries
    'CONF CHAMP': 24, // No worries
    'PRO BOWL': 25, // No worries
    'SUPER BOWL': 26, // No worries
  };

  build() {
    let dropdown = jQuery("#nflGameWeek").find(".slo-dropdown-menu");
    for (let key in this.nflGameWeeks) {
      let menuItem = `<div class="slo-dropdown-item" data-type="week" data-value="${this.nflGameWeeks[key]}" >${key}</div>`;
      dropdown.append(menuItem);
    }

    // #2 The actual request
    this.getCurrentWeekAndFetchOdds();

    jQuery("#nflData #nflTypeText").html(this.type.toUpperCase());
    jQuery("#nflData #nflTypeText").data("type", this.type);

  }

  getCurrentWeekAndFetchOdds() {
    let request = jQuery.ajax({
      url: `https://api.sportsdata.io/v3/nfl/scores/json/Timeframes/current?key=<?= $this->config['apiKeys']['nfl']['schedule'] ?>`
    });

    request.done((data) => {
      this.season = data[0].ApiSeason;
      this.currentWeek = data[0].ApiWeek;
      this.fetchData(
        `https://api.sportsdata.io/v3/nfl/odds/json/GameOddsByWeek/${this.season}/${this.currentWeek}?key=<?= $this->config['apiKeys']['nfl']['liveOdds'] ?>`,
        this.type
      );
    });
  }

}

(function() {
  jQuery(document).ready(() => {
    let nfl = new NFL();
    nfl.sport = 'nfl';
    nfl.type  = 'MoneyLine';
    nfl.build();

    // Events Listeners
    // Change Week
    jQuery(".slo-dropdown-item").click(function(e) {
      if (jQuery(this).data("type") == 'week') {
        let week = jQuery(this).data('value');
        jQuery('#nflWeekText').html(jQuery(this).html());
        nfl.fetchData(
          `https://api.sportsdata.io/v3/nfl/odds/json/GameOddsByWeek/${this.season}/${this.currentWeek}?key=<?= $this->config['apiKeys']['nfl']['liveOdds'] ?>`,
          jQuery("#nflData #nflTypeText").data("type")
        );
      }
    });

    // Change book type
    jQuery(".oddsNfl.slo-dropdown-item").on("click", function(e) {
      if ( jQuery(this).data("type") == "type" ) {
        nfl.type = jQuery(this).data("value");
        text = jQuery(this).html();
        jQuery("#nflData #nflTypeText").html(text.toUpperCase());
      }
      if (nfl.resultData)
        nfl.setSloOddsView(nfl.resultData, nfl.sport, nfl.type);
    });
  }); // j(document).ready
})();

</script>