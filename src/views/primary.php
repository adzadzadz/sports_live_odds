<?php 

?>

<style>
  #sloContainer {
    max-width: 100% !important;
  }
  #sloContainer a {
    font-family: 'Roboto Condensed', sans-serif;
    text-decoration: none;
  }
  #tabContent .dropdown {
    border: 1px #919191 solid;
  }
  #tabContent .dropdown .btn.dropdown-toggle {
    width: 100%;
    cursor: pointer;
  }
</style>

<style>
  section.teams .mimicTable div.cell{
    border: 1px grey solid;
    font-size: 0.8em;
    color: #757575;
  }
</style>

<script src="<?= plugins_url('../assets/node_modules/moment/moment.js',__FILE__) ?>"></script>
<script src="<?= plugins_url('../assets/node_modules/moment-timezone/moment-timezone.js',__FILE__) ?>"></script>
<script>
  moment.tz.add(["America/New_York|EST EDT EWT EPT|50 40 40 40|01010101010101010101010101010101010101010101010102301010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010|-261t0 1nX0 11B0 1nX0 11B0 1qL0 1a10 11z0 1qN0 WL0 1qN0 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1qN0 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1qN0 WL0 1qN0 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1qN0 WL0 1qN0 11z0 1o10 11z0 RB0 8x40 iv0 1o10 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1qN0 WL0 1qN0 11z0 1o10 11z0 1o10 11z0 1o10 11z0 1o10 1fz0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1fz0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1fz0 1a10 1fz0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1fz0 1cN0 1cL0 1cN0 1cL0 s10 1Vz0 LB0 1BX0 1cN0 1fz0 1a10 1fz0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 1cN0 1fz0 1a10 1fz0 1cN0 1cL0 1cN0 1cL0 1cN0 1cL0 14p0 1lb0 14p0 1nX0 11B0 1nX0 11B0 1nX0 14p0 1lb0 14p0 1lb0 14p0 1nX0 11B0 1nX0 11B0 1nX0 14p0 1lb0 14p0 1lb0 14p0 1lb0 14p0 1nX0 11B0 1nX0 11B0 1nX0 14p0 1lb0 14p0 1lb0 14p0 1nX0 11B0 1nX0 11B0 1nX0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Rd0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0 Op0 1zb0|21e6"]);
  var currentStartDate = moment().tz("America/New_York").format("YYYY-MM-DD");
</script>

<div id="sloContainer">
  <div class="row">
    <div class="col-md-12">
      <h1>Live Sports Betting Odds</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
    
      <nav id="sports">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link" id="NFL-tab" data-toggle="tab" href="#NFL" role="tab" aria-controls="NFL" aria-selected="true">NFL</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="NCAAF-tab" data-toggle="tab" href="#NCAAF" role="tab" aria-controls="NCAAF" aria-selected="false">NCAAF</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" id="MLB-tab" data-toggle="tab" href="#MLB" role="tab" aria-controls="MLB" aria-selected="false">MLB</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="NBA-tab" data-toggle="tab" href="#NBA" role="tab" aria-controls="NBA" aria-selected="false">NBA</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="WNBA-tab" data-toggle="tab" href="#WNBA" role="tab" aria-controls="WNBA" aria-selected="false">WNBA</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="NCAAB-tab" data-toggle="tab" href="#NCAAB" role="tab" aria-controls="NCAAB" aria-selected="false">NCAAB</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="NHL-tab" data-toggle="tab" href="#NHL" role="tab" aria-controls="NHL" aria-selected="false">NHL</a>
          </li>
        </ul>
        <div class="tab-content" id="tabContent">
          <div class="tab-pane fade" id="NFL" role="tabpanel" aria-labelledby="NFL-tab">
            <?= $this->render('primary/nfl-content.php') ?>
          </div>
          <div class="tab-pane fade" id="NCAAF" role="tabpanel" aria-labelledby="NCAAF-tab">...</div>
          <div class="tab-pane fade show active" id="MLB" role="tabpanel" aria-labelledby="MLB-tab">
            <?= $this->render('primary/mlb-content.php') ?>
          </div>
          <div class="tab-pane fade" id="NBA" role="tabpanel" aria-labelledby="NBA-tab">...</div>
          <div class="tab-pane fade" id="WNBA" role="tabpanel" aria-labelledby="WNBA-tab">...</div>
          <div class="tab-pane fade" id="NCAAB" role="tabpanel" aria-labelledby="NCAAB-tab">...</div>
          <div class="tab-pane fade" id="NHL" role="tabpanel" aria-labelledby="NHL-tab">
          <?= $this->render('primary/nhl-content.php') ?>
          </div>
        </div>
      </nav>

    </div>
  </div>
</div>