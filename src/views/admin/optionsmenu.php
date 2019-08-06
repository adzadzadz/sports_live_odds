<?php 



?>

<style>
  #basic {
    padding: 120px 60px;
  }
</style>

<div class="container">
  <section id="basic">
    <button id="updateDB" class="btn btn-danger btn-lg">Update Database</button>
    <button id="dlData" class="btn btn-primary btn-lg">Download Sports Data</button>
  </section>
</div>

<script>
(function() {

  jQuery("#dlData").on('click', function(e) {
    alert("<?= $this->config['sportsdataio']['apiKey'] ?>");
  });

})();
</script>