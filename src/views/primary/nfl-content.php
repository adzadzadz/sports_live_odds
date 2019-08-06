<style>
  section.teams .mimicTable div.cell{
    border: 1px grey solid;
    font-size: 0.6em;
    color: #757575;
  }
</style>

<header class="col-md-12">
  <section class="filter row">
    <div class="dropdown col-md-3">
      <div class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Date
      </div>

      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <a class="dropdown-item" href="#">Action</a>
        <a class="dropdown-item" href="#">Another action</a>
        <a class="dropdown-item" href="#">Something else here</a>
      </div>
    </div>
    <div class="dropdown col-md-3">
      <div class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Type
      </div>

      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <a class="dropdown-item" href="#">Action</a>
        <a class="dropdown-item" href="#">Another action</a>
        <a class="dropdown-item" href="#">Something else here</a>
      </div>
    </div>
    <div class="dropdown col-md-5">
      <div class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Duration
      </div>

      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <a class="dropdown-item" href="#">Action</a>
        <a class="dropdown-item" href="#">Another action</a>
        <a class="dropdown-item" href="#">Something else here</a>
      </div>
    </div>
  </section>
</header>
<div class="content col-md-12">
  <section class="teams row">
    <div class="col-md-12 mimicTable">
      <div class="row">
        <div class="cell col-md-3">Schedule</div>
        <div class="col-md-3">
          <div class="row">
            <div class="cell col-md-6">Open</div>
            <div class="cell col-md-6">Consensus</div>      
          </div>
        </div>
        <div class="col-md-6">
          <div class="row">
            <div class="cell col-md-2">Westgate</div>
            <div class="cell col-md-2">Caesars</div>
            <div class="cell col-md-2">Pinnacle</div>
            <div class="cell col-md-2">5Dimes</div>
            <div class="cell col-md-2">BetOnline</div>
          </div>
        </div>
      </div>
        
    </div>
  </section>
</div>

<script>
(function() {

  /**
   * TODO: 
   * Get Schedules
   * 
   */

  var request = jQuery.ajax({
    url: "https://api.sportsdata.io/v3/nfl/scores/json/AllTeams?key=8d83eeb36ceb4cee8072a94f7f85f0e1"
  });
  
  request.done((data) => {
    
  })
})();
</script>