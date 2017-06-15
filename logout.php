<?php
session_start();
 ?>
<!DOCTYPE html>
<link rel="stylesheet" href="css/master.css">
<html>
  <head>
    <meta charset="utf-8">
    <title>Logging Out</title>
  </head>
  <body>
    <?php
      session_destroy();
    ?>
    <script>
    window.setTimeout( () => {
        window.location = 'index.php';
      }, 2100);
    </script>
    <div>
          <p>You have been logged out.</p>
    </div>

  </body>
</html>
