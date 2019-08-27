<section id="ncaafData" class="sloSportData">
  <div class="col-md-12">
    <div class="fetchTime"></div>
    <div class="fetchUrl"></div>
  </div>
  <header class="col-md-8">
    <section class="filter row slo-dropshadow slo-filter-wrap">
      <div id="ncaafGameWeek" class="slo-dropdown col-md-6">
        <div class="slo-dropdown-toggle filter-label" href="#" role="button" id="nflWeekDropdown">
          <span id="ncaafWeekText">Week</span> <i class="fa fa-chevron-down"></i>
        </div>

        <div class="slo-dropdown-menu slo-hidden"></div>
      </div>
      <div class="slo-dropdown col-md-6">
        <div class="slo-dropdown-toggle filter-label" href="#" role="button" id="ncaafTypeDropdown">
          <span id="ncaafTypeText" data-type="type">Type</span> <i class="fa fa-chevron-down"></i>
        </div>

        <div class="slo-dropdown-menu slo-hidden">
          <div class="oddsNCAAF slo-dropdown-item" data-type="type" data-value="PointSpread" >SPREAD</div>
          <div class="oddsNCAAF slo-dropdown-item" data-type="type" data-value="OverUnder" >TOTAL</div>
          <div class="oddsNCAAF slo-dropdown-item" data-type="type" data-value="MoneyLine" >MONEYLINE</div>
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
          <section id="ncaafContent"></section>
        </div>
      </section>
    </div>
  </div>
</section>

<script>

class NCAAF extends SLO {
  season = null;
  currentWeek = null;
  ncaafGameWeeks = {
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
    'BOWLS': 16,
  };

  build() {
    let dropdown = jQuery("#ncaafGameWeek").find(".slo-dropdown-menu");
    for (let key in this.ncaafGameWeeks) {
      let menuItem = `<div class="slo-dropdown-item" data-type="week" data-value="${this.ncaafGameWeeks[key]}" >${key}</div>`;
      dropdown.append(menuItem);
    }

    this.getCurrentWeekAndFetchOdds();

    jQuery("#ncaafData #ncaafTypeText").html(this.type.toUpperCase());
    jQuery("#ncaafData #ncaafTypeText").data("type", this.type);
  }

  getCurrentWeekAndFetchOdds() {
    let request = jQuery.ajax({
      // https://api.sportsdata.io/v3/cfb/scores/json/CurrentSeasonDetails?key=50e9c9df37694e4d9a4953ac3104d246
      url: `https://api.sportsdata.io/v3/cfb/scores/json/CurrentSeasonDetails?key=<?= $this->config['apiKeys']['ncaaf']['schedule'] ?>`
    });

    request.done((data) => {
      this.season = data.ApiSeason;
      this.currentWeek = data.ApiWeek;
      this.fetchData(
        `https://api.sportsdata.io/v3/cfb/odds/json/GameOddsByWeek/${this.season}/${this.currentWeek}?key=<?= $this->config['apiKeys']['ncaaf']['liveOdds'] ?>`,
        this.type
      );
    });
  }

}

(function() {
  jQuery(document).ready(() => {
    
    let ncaaf = new NCAAF();
    ncaaf.sport = 'ncaaf';
    ncaaf.build();

    // Event handlers
    // Change week
    jQuery(".slo-dropdown-item").click(function(e) {
      if (jQuery(this).data("type") == 'week') {
        let week = jQuery(this).data('value');
        jQuery('#ncaafWeekText').html(jQuery(this).html());
        ncaaf.fetchData(
          `https://api.sportsdata.io/v3/cfb/odds/json/GameOddsByWeek/${ncaaf.season}}/${week}?key=<?= $this->config['apiKeys']['ncaaf']['liveOdds'] ?>`,
          jQuery("#ncaafData #ncaafTypeText").data("type")
        );
      }
    });
    
    // Change book type
    jQuery(".oddsNCAAF.slo-dropdown-item").on("click", function(e) {
      if ( jQuery(this).data("type") == "type" ) {
        ncaaf.type = jQuery(this).data("value");
        text = jQuery(this).html();
        jQuery("#ncaafData #ncaafTypeText").html(text.toUpperCase());
      }
      if (ncaaf.resultData)
        ncaaf.setSloOddsView(ncaaf.resultData, ncaaf.sport, ncaaf.type);
    });
 
  }); // jQuery(document).ready
})();

</script>