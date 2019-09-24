<script>

  class CSV {

  }

</script>

<?php 

  if (empty($_GET)) {
    echo $this->render('csv/default.php');
  } else {
    echo $this->render('csv/fetch.php');
  }

?>
