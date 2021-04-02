<?php
 
/* Attempt to connect to Postgresql database */
    $db_connection = pg_connect("host=localhost dbname=arts user=app password=app");
 
// Check connection
if($db_connection === false){
    die("ERROR: Could not connect. ");
}else{
  echo "la connexion à psql a réussi";
}
?>
