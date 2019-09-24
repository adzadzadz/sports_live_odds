<section id="nflData" class="sloSportData">
  <header class="col-md-8">
    <section class="filter row slo-filter-wrap">
      <div id="nflGameWeek" class="slo-dropdown col-md-6 slo-filter">
        <div class="slo-dropdown-toggle filter-label" href="#" role="button" id="nflWeekDropdown">
          <span id="nflWeekText">Week</span> <i class="fa fa-chevron-down"></i>
        </div>
  
        <div class="slo-dropdown-menu slo-hidden"></div>
      </div>
      <div class="slo-dropdown col-md-6 slo-filter">
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
  <section id="nflContentContainer"></section>
</section>

<script>

class NFL extends SLO {

  season = null;
  currentWeek = null;
  nflGameWeeks = {
    'HOF'   : {season : "PRE", week: 0},
    'PRE1'  : {season : "PRE", week: 1},
    'PRE2'  : {season : "PRE", week: 2},
    'PRE3'  : {season : "PRE", week: 3},
    'PRE4'  : {season : "PRE", week: 4}, 
    'WEEK1' : {season : "REG", week: 1},
    'WEEK2' : {season : "REG", week: 2},
    'WEEK3' : {season : "REG", week: 3},
    'WEEK4' : {season : "REG", week: 4},
    'WEEK5' : {season : "REG", week: 5}, 
    'WEEK6' : {season : "REG", week: 6},
    'WEEK7' : {season : "REG", week: 7},
    'WEEK8' : {season : "REG", week: 8},
    'WEEK9' : {season : "REG", week: 9}, 
    'WEEK10': {season : "REG", week: 10},
    'WEEK11': {season : "REG", week: 11},
    'WEEK12': {season : "REG", week: 12},
    'WEEK13': {season : "REG", week: 13},
    'WEEK14': {season : "REG", week: 14},
    'WEEK15': {season : "REG", week: 15}, 
    'WEEK16': {season : "REG", week: 26},
    'WEEK17': {season : "REG", week: 17},
    // 'WILD CARD': 22, // No worries
    // 'DIVISION ROUND': 23, // No worries
    // 'CONF CHAMP': 24, // No worries
    // 'PRO BOWL': 25, // No worries
    // 'SUPER BOWL': 26, // No worries
  };

  build() {
    let dropdown = jQuery("#nflGameWeek").find(".slo-dropdown-menu");
    for (let key in this.nflGameWeeks) {
      let menuItem = `<div class="slo-dropdown-item" data-type="week" data-season="${this.nflGameWeeks[key]['season']}" data-week="${this.nflGameWeeks[key]['week']}" >${key}</div>`;
      dropdown.append(menuItem);
    }

    // #2 The actual request
    this.getCurrentWeekAndFetchOdds();

    jQuery("#nflData #nflTypeText").html(this.typeText.toUpperCase());
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
    nfl.type  = 'PointSpread';
    nfl.typeText = 'SPREAD';
    nfl.build();

    // Events Listeners
    // Change Week
    jQuery(".slo-dropdown-item").click(function(e) {
      if (jQuery(this).data("type") == 'week') {
        let week = jQuery(this).data('week');
        let season = jQuery(this).data('season');
        jQuery('#nflWeekText').html(jQuery(this).html());
        nfl.fetchData(
          `https://api.sportsdata.io/v3/nfl/odds/json/GameOddsByWeek/2019${season}/${week}?key=<?= $this->config['apiKeys']['nfl']['liveOdds'] ?>`,
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