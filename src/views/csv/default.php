<section id="sloCsvDefault">
  <div class="row">
    <div class="col-md-4">
      <div id="sportDropdown" class="slo-dropdown col-md-12 slo-filter">
        <div class="slo-dropdown-toggle filter-label" href="#" role="button" id="sportDropdown">
          <span id="nflWeekText">NFL</span> <i class="fa fa-chevron-down"></i>
        </div>
        <div class="slo-dropdown-menu slo-hidden">
          <div class="slo-dropdown-item" data-type="sport" data-value="nfl" >NFL</div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div id="nflGameWeek" class="slo-dropdown col-md-12 slo-filter">
        <div class="slo-dropdown-toggle filter-label" href="#" role="button" id="nflWeekDropdown">
          <span id="weekText">Week</span> <i class="fa fa-chevron-down"></i>
        </div>
        <div class="slo-dropdown-menu slo-hidden"></div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="slo-dropdown col-md-12 slo-filter">
        <div class="slo-dropdown-toggle filter-label" href="#" role="button" id="nflTypeDropdown">
          <span id="typeText" data-type="type">Type</span> <i class="fa fa-chevron-down"></i>
        </div>

        <div class="slo-dropdown-menu slo-hidden">
          <div class="oddsNfl slo-dropdown-item" data-type="type" data-value="PointSpread" >SPREAD</div>
          <div class="oddsNfl slo-dropdown-item" data-type="type" data-value="OverUnder" >TOTAL</div>
          <div class="oddsNfl slo-dropdown-item" data-type="type" data-value="MoneyLine" >MONEYLINE</div>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <button id="generateCsvBtn" class="slo-btn-csv">GENERATE CSV</button>
      <button id="downloadCsv" class="slo-btn-csv">DOWNLOAD</button>
    </div>
  </div>
</section>

<?php 

  global $wp;
  // echo home_url( $wp->request );
  $nonce = wp_create_nonce("generate_csv_nonce");
	$ajaxUrl = admin_url('admin-ajax.php');

?>

<div id="configContainer" data-url="<?= $ajaxUrl ?>" data-sport="nfl" data-week="" data-season="" data-type="" data-nonce="<?= $nonce ?>"></div>

<script async>

  class Default extends CSV {

    sport = 'nfl';

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
    }

  }

  (function () {

    jQuery(document).ready( function() {
      let csv = new Default;
      csv.build();

      jQuery("#generateCsvBtn").click( function(e) {
          e.preventDefault(); 
          
          let dataContainer = jQuery("#configContainer");
          let url = dataContainer.data("url");
          let sport = dataContainer.data("sport");
          let week = dataContainer.data("week");
          let season = dataContainer.data("season");
          let type = dataContainer.data("type");
          let nonce = dataContainer.data("nonce");
          console.log(url, sport, week, type);

          jQuery.ajax({
            type : "post",
            // dataType : "json",
            url : url,
            data : {action : "generate_csv", sport : sport, season : season, week : week, type : type, nonce : nonce},
            success: function(response) {
              if(response.type == "success") {
                console.log("success", response);
              } else {
                console.log("fail");
              }
            }
          });
      });

      jQuery(".slo-dropdown-item").click(function(e) {
        let dataContainer = jQuery("#configContainer");
        if (jQuery(this).data("type") == 'week') {
          let season = jQuery(this).data('season');
          let week = jQuery(this).data('week');
          dataContainer.data("season", season);
          dataContainer.data("week", week);
          let weekText = jQuery(this).html();
          jQuery("#weekText").html(weekText);
        } else if (jQuery(this).data("type") == 'type') {
          let type = jQuery(this).data('value');
          dataContainer.data("type", type);
          let typeText = jQuery(this).html();
          jQuery("#typeText").html(typeText);
        }
      });

    });

  })();

</script>