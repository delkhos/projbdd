<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>HTML is processed</title>
  </head>
  <body>
    <p>The page</p>
    <?php echo "PHP is processed as well" ?>
    <ul>
      <?php
      for($i = 0; $i < 10; $i++) {
        print("<li>The $i th item</li>");
      }
      ?>
    </ul>
  </body>
</html>
