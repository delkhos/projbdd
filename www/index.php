<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Main page</title>
  </head>
  <body>
    
    <a href="login.php">Log in</a>

    <?php echo "PHP is processed as well" ?>
    <?php echo phpinfo() ?>
    <ul>
      <?php
      for($i = 0; $i < 10; $i++) {
        print("<li>The $i th item</li>");
      }
      ?>
    </ul>
  </body>
</html>
