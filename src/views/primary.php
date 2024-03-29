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

<div id="sloContainer">
  <div class="row">
    <div class="col-12">
      <h1>Live Sports Betting Odds</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
    
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