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

<section id="mlbData" class="sloSportData">
  <header class="col-md-8">
    <section class="filter row slo-dropshadow slo-filter-wrap">
      <div class="col-md-6">
        <div class="row">
          <div id="datePrev" class="dateChanger btn btn-sm btn-success col-md-3" data-type="prev">PREV</div>
          <div id="dateDisplay" class="col-md-6 filter-label">2019-05-03</div>
          <div id="dateNext" class="dateChanger btn btn-sm btn-success col-md-3" data-type="next">NEXT</div>
        </div>
      </div>
      <div class="slo-dropdown col-md-6">
        <div class="slo-dropdown-toggle filter-label" href="#" role="button" id="mlbTypeDropdown">
          <span id="mlbTypeText" data-type="type">Type</span> <i class="fa fa-chevron-down"></i>
        </div>

        <div class="slo-dropdown-menu slo-hidden">
          <div class="oddsMlb slo-dropdown-item" data-type="type" data-value="PointSpread" >SPREAD</div>
          <div class="oddsMlb slo-dropdown-item" data-type="type" data-value="OverUnder" >TOTAL</div>
          <div class="oddsMlb slo-dropdown-item" data-type="type" data-value="MoneyLine" >MONEYLINE</div>
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
          <section id="mlbContent"></section>
        </div>
      </section>
    </div>
  </div>
</section>

<script src="<?= plugins_url('../../assets/node_modules/moment/moment.js',__FILE__) ?>"></script>
<script src="<?= plugins_url('../../assets/node_modules/moment-timezone/moment-timezone.js',__FILE__) ?>"></script>
<script>
  moment.tz.add(["America/New_York|EST EDT EWT EPT|50 40 40 40|01010101010101010101010101010101010101010101010102301010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010|-261t0 1nX0 11B0 1nX0 11B0 1qL0 1a10 11z0 1qN0 WL0 1qN0 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1qN0 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1qN0 WL0 1qN0 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1qN0 WL0 1qN0 11z0 1o10 11z0 RB0 8x40 iv0 1o10 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1qN0 WL0 1qN0 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1o10 1fz0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1fz0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1fz0 1a10 1fz0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1fz0 1cN0 1cL0 1cN0 1cL0 s10 1Vz0 LB0 1BX0 1cN0 1fz0 1a10 1fz0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1fz0 1a10 1fz0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 14p0 1lb0 14p0 1nX0 11B0 1nX0 11B0 1nX0 14p0 1lb0 14p0 1lb0 14p0 1nX0 11B0 1nX0 11B0 1nX0 14p0 1lb0 14p0 1lb0 14p0 1lb0 14p0 1nX0 11B0 1nX0 11B0 1nX0 14p0 1lb0 14p0 1lb0 14p0 1nX0 11B0 1nX0 11B0 1nX0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0|21e6"]);
  var currentStartDate = moment().tz("America/New_York").format("YYYY-MM-DD");
</script>
<script>
(function() {
  let sport = "mlb";
  let type = "MoneyLine";
  let resultData = null;
  let intervalId = null;

  jQuery(document).ready(() => {

    // #1 Date Setup
    jQuery("#mlbData #dateDisplay").text(getClosestDateFromList(new Date(currentStartDate)));

    function getClosestDateFromList(selectedDate, addDays = 0) {
      let dateList = <?= $dateList ?>;
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
        "https://api.sportsdata.io/v3/mlb/odds/json/GameOddsByDate/" + newDate + "?key=<?= $this->config['apiKeys']['mlb']['liveOdds'] ?>",
        jQuery("#mlbData #mlbTypeText").data("type")
      );
    });

    let selectedDate = jQuery("#mlbData #dateDisplay").text();
    
    // #2 The actual request
    fetchData(
      "https://api.sportsdata.io/v3/mlb/odds/json/GameOddsByDate/" + selectedDate + "?key=<?= $this->config['apiKeys']['mlb']['liveOdds'] ?>",
      type
    );

    function fetchData(url, type) {
      function request() {
        let request = jQuery.ajax({
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
      intervalId = setInterval(function(){
        request();
      }, 120000); // 2mins
    }
    
    // #3 Book Type Setup
    jQuery("#mlbData #mlbTypeText").html(type.toUpperCase());
    jQuery("#mlbData #mlbTypeText").data("type", type);
    jQuery(".oddsMlb.slo-dropdown-item").on("click", function(e) {
      if ( jQuery(this).data("type") == "type" ) {
        type = jQuery(this).data("value");
        text = jQuery(this).html();
        jQuery("#mlbData #mlbTypeText").html(text.toUpperCase());
        jQuery("#mlbData #mlbTypeText").data("type", type);
      }
      if (resultData)
        setSloOddsView(resultData, sport, type);
    });
    
  });
  
})();
</script>