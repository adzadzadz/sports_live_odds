<?php 



?>

<style>
  #basic {
    padding: 120px 60px;
  }
  .btn {
    color: #fff !important;
  }
</style>

<div class="container">
  <section id="basic">
    <a id="updateDatabase" class="btn btn-danger btn-lg" href="<?= admin_url('admin.php?page=slo-update-db') ?>">Update Sports Data</a>
    <a id="dlData" class="btn btn-primary btn-lg" href="<?= admin_url('admin.php?page=slo-update-data') ?>">Update Sports Data</a>
  </section>
</div>