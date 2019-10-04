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

<section id="mlbData" class="sloSportData row">
  <header class="col-md-8">
    <section class="row slo-filter-wrap">
      <div class="col-md-6 slo-filter">
        <div class="row">
          <div id="datePrev" class="dateChanger filter-label btn btn-sm btn-success col-2" data-type="prev">
            <i class="fa fa-chevron-left"></i>
          </div>
          <div id="dateDisplay" class="col-8 filter-label">2019-05-03</div>
          <div id="dateNext" class="dateChanger filter-label btn btn-sm btn-success col-2" data-type="next">
            <i class="fa fa-chevron-right"></i>
          </div>
        </div>
      </div>
      <div class="slo-dropdown col-md-6 slo-filter">
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
  <section id="mlbContentContainer" class="col-md-12"></section>
</section>

<script async>
  class MLB extends SLO {
    build () {
      // #1 Date Setup
      jQuery("#mlbData #dateDisplay").text(this.getClosestDateFromList(new Date(sloCurrentStartDate)));
      let selectedDate = jQuery("#mlbData #dateDisplay").text();

      // #2 The actual request
      this.fetchData(
        "https://api.sportsdata.io/v3/mlb/odds/json/GameOddsByDate/" + selectedDate + "?key=<?= $this->config['apiKeys']['mlb']['liveOdds'] ?>",
        this.type
      );

      // #3 Book Type Setup
      jQuery("#mlbData #mlbTypeText").html(this.type.toUpperCase());
      jQuery("#mlbData #mlbTypeText").data("type", this.type);
      
    }

    getClosestDateFromList(selectedDate, addDays = 0) {
      let dateList = <?= $dateList ?>;
      let formatted_date = null;
      let isDateValid = null;
      if (addDays !== 0) {
        function setDateSLO() {
          selectedDate.setDate( selectedDate.getDate() + addDays );
          formatted_date = selectedDate.getFullYear() + "-" + (selectedDate.getMonth() + 1) + "-" + ("0" + selectedDate.getDate()).slice(-2);
          isDateValid = dateList.includes(formatted_date);
        }
        setDateSLO();
        // add days while date is not valid and addDays is more than 0
        while (!isDateValid && addDays > 0) {
          setDateSLO();
        }
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
  }

  (function() {
    jQuery(document).ready(() => {
      let mlb = new MLB();
      mlb.sport = 'mlb';
      mlb.sportsBooks = ['Pinnacle', 'WestgateSuperbookNV', 'DraftKings', 'FanDuel', 'SugarHousePA'];
      mlb.build();

      // Event Listeners
      // Change Date
      jQuery(".dateChanger").on('click', function(e) {
        let dateElem = jQuery(this);
        let dateDisplay = jQuery('#dateDisplay');
        let selected = new Date(dateDisplay.text())
        var newDate = 0;
        if (dateElem.data('type') == "prev") {
          newDate = mlb.getClosestDateFromList(selected, - 1);
        }
        if (jQuery(this).data('type') == "next") {
          newDate = mlb.getClosestDateFromList(selected, 1);
        }
        dateDisplay.text(newDate);
        mlb.fetchData(
          "https://api.sportsdata.io/v3/mlb/odds/json/GameOddsByDate/" + newDate + "?key=<?= $this->config['apiKeys']['mlb']['liveOdds'] ?>",
          jQuery("#mlbData #mlbTypeText").data("type")
        );
      });

      // Change Book Type
      jQuery(".oddsMlb.slo-dropdown-item").on("click", function(e) {
        if ( jQuery(this).data("type") == "type" ) {
          mlb.type = jQuery(this).data("value");
          text = jQuery(this).html();
          jQuery("#mlbData #mlbTypeText").html(text.toUpperCase());
          jQuery("#mlbData #mlbTypeText").data("type", mlb.type);
        }
        if (mlb.resultData)
          mlb.setSloOddsView(mlb.resultData, mlb.sport, mlb.type);
      });
    });
  })();
</script>