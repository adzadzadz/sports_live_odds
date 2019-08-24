<section id="ncaafData" class="sloSportData">
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
                <div class="cell slo-col-hack-5">Draftkings</div>
                <div class="cell slo-col-hack-5">FanDuel</div>
                <div class="cell slo-col-hack-5">SugerHouse</div>
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
(function() {
  let sport = 'ncaaf';
  let type  = 'MoneyLine';
  let intervalId = null;
  let j = jQuery;
  var ncaafGameWeeks = {
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

  j(document).ready(() => {
    let dropdown = j("#ncaafGameWeek").find(".slo-dropdown-menu");
    for (let key in ncaafGameWeeks) {
      let menuItem = `<div class="slo-dropdown-item" data-type="week" data-value="${ncaafGameWeeks[key]}" >${key}</div>`;
      dropdown.append(menuItem);
    }
    resultData = null;
    j(".slo-dropdown-item").click(function(e) {
      if (j(this).data("type") == 'week') {
        let week = j(this).data('value');
        j('#ncaafWeekText').html(j(this).html());
        fetchSloData(
          // https://api.sportsdata.io/v3/cfb/odds/xml/GameOddsByWeek/2019/1?key=a885dd5c8a4740b396bb75a644c278a4
          `https://api.sportsdata.io/v3/ncaaf/odds/json/GameOddsByWeek/2019/${week}?key=<?= $this->config['apiKeys']['ncaaf']['liveOdds'] ?>`,
          jQuery("#ncaafData #ncaafTypeText").data("type")
        );
      }
    });

    // #2 The actual request
    fetchSloData(
      // https://api.sportsdata.io/v3/cfb/odds/xml/GameOddsByWeek/2019/1?key=a885dd5c8a4740b396bb75a644c278a4
      `https://api.sportsdata.io/v3/cfb/odds/json/GameOddsByWeek/2019/${ncaafGameWeeks['WEEK1']}?key=<?= $this->config['apiKeys']['ncaaf']['liveOdds'] ?>`,
      type
    );

    jQuery("#ncaafData #ncaafTypeText").html(type.toUpperCase());
    jQuery("#ncaafData #ncaafTypeText").data("type", type);
    j(".oddsNCAAF.slo-dropdown-item").on("click", function(e) {
      if ( j(this).data("type") == "type" ) {
        type = j(this).data("value");
        text = j(this).html();
        j("#ncaafData #ncaafTypeText").html(text.toUpperCase());
      }
      if (resultData)
        setSloOddsView(resultData, sport, type);
    });

    function fetchSloData(url, type) {
      function request() {
        var request = j.ajax({
          url: url
        });

        request.done((data) => {
          resultData = data;
          setSloOddsView(resultData, sport, type);
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
  
  }); // j(document).ready
})();
</script>