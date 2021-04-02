<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Logged page</title>
  </head>
  <body>
      <?php
      // The global $_POST variable allows you to access the data sent with the POST method by name
      // To access the data sent with the GET method, you can use $_GET
      $usn = htmlspecialchars($_POST['usn']);
      $pwd  = htmlspecialchars($_POST['pwd']);

      echo  $usn, ' ', $pwd;
    ?> 
  </body>
</html>
