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
        <div class="dropdown-item" data-value="" >Action</div>
        <div class="dropdown-item" data-value="" >Another action</div>
        <div class="dropdown-item" data-value="" >Something else here</div>
      </div>
    </div>
    <div class="dropdown col-md-3">
      <div class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Type
      </div>

      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <a class="dropdown-item" data-value="" >Action</a>
        <a class="dropdown-item" data-value="" >Another action</a>
        <a class="dropdown-item" data-value="" >Something else here</a>
      </div>
    </div>
    <div class="dropdown col-md-5">
      <div class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Duration
      </div>

      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <a class="dropdown-item" data-value="" >Action</a>
        <a class="dropdown-item" data-value="" >Another action</a>
        <a class="dropdown-item" data-value="" >Something else here</a>
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

  console.log(jQuery('.dropdown-item').data('value'));

  var request = jQuery.ajax({
    url: "https://api.sportsdata.io/v3/mlb/odds/json/GameOddsByDate/2019-08-01?key=ecea4068ef7f4c78899b2b0867619c9d"
  });
  
  request.done((data) => {
    console.log(data);
    // data.foreach(i => {
    //   console.log(i);
    // });
  })
})();
</script>