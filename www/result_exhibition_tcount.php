

<?php
  // Initialize the session
  session_start();
   
  // Check if the user is logged in, if not then redirect him to login page
  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true ){
      header("location: login.php");
      exit;
  }
  
  // Include config file
  require_once "config.php";
  ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Search</title>
    <!-- jQuery library -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <style>
      body{ font: 14px sans-serif; text-align: center; }
    </style>
  </head>
  <body>
    <h1 class="my-5"><b>ARTS EXPLORER.</b></h1>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <span class="navbar-brand mb-0 ">Logged in as: <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</span>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav">
            <a class="nav-item nav-link " href="index.php">Home</a>
            <a class="nav-item nav-link " href="reset-password.php">Reset your password</a>
            <a class="nav-item nav-link " href="logout.php">Sign out</a>
            <a class="nav-item nav-link " href="advanced-search.php">Advanced Search</a>
          </div>
        </div>
        <form class="form-inline" method="post" action="search.php">
          <input class="form-control mr-sm-2" name="search" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
      </div>
    </nav>
    <!--
      corps de la recherche
      -->  
    <h3>Search results:</h3>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">Museum</th>
          <th scope="col">Start date</th>
          <th scope="col">End date</th>
          <th scope="col">Artist</th>
        </tr>
      </thead>
      <tbody>
        <?php
          if ($_SERVER["REQUEST_METHOD"] == "POST"){
          $sql = "";
          if(  strlen($_POST["cename_search"])>0){
            $sql = $sql . "(nom_expo LIKE :cename_search1 OR SIMILARITY(nom_expo,:cename_search2) > 0.4) ";
          };
          if(  strlen($_POST["cework_search"])>0){
            if (strlen($sql) != 0) {
              $sql = $sql . "AND ";
            }
            ;
            $sql = $sql . "(expo_id IN (SELECT exposition FROM Oeuvre WHERE nom_oeuvre LIKE :cework_search1 OR SIMILARITY(nom_oeuvre,:cework_search2) > 0.4)) ";
          };
          if(  strlen($_POST["cemuseum_search"])>0){
            if (strlen($sql) != 0) {
              $sql = $sql . "AND ";
            }
            ;
            $sql = $sql . "(musee LIKE :cemuseum_search1 OR SIMILARITY(musee,:cemuseum_search2) > 0.4) ";
          };
          if(  strlen($_POST["cecountry_search"])>0){
            if (strlen($sql) != 0) {
              $sql = $sql . "AND ";
            }
            ;
            $sql = $sql . "(pays LIKE :cecountry_search1 OR SIMILARITY(pays,:cecountry_search2) > 0.4) ";
          };
          if (strlen($sql) >0){
            $sql = "WHERE ".$sql;
          };
          
          $stmt = $pdo->prepare( "SELECT DISTINCT * FROM Exposition_temporaire_pays ".$sql);
          
          if(   strlen($_POST["cename_search"])>0){
            $cename_query = trim($_POST["cename_search"]);
            $cename_prepared = "%".$cename_query."%"; 
            $stmt->bindParam(":cename_search1", $cename_prepared, PDO::PARAM_STR);
            $stmt->bindParam(":cename_search2", $cename_query, PDO::PARAM_STR);
          };
          if(  strlen($_POST["cework_search"])>0){
            $cework_query = trim($_POST["cework_search"]);
            $cework_prepared = "%".$cework_query."%"; 
            $stmt->bindParam(":cework_search1", $cework_prepared, PDO::PARAM_STR);
            $stmt->bindParam(":cework_search2", $cework_query, PDO::PARAM_STR);
          };
          if(  strlen($_POST["cemuseum_search"])>0){
            $cemuseum_query = trim($_POST["cemuseum_search"]);
            $cemuseum_prepared = "%".$cemuseum_query."%"; 
            $stmt->bindParam(":cemuseum_search1", $cemuseum_prepared, PDO::PARAM_STR);
            $stmt->bindParam(":cemuseum_search2", $cemuseum_query, PDO::PARAM_STR);
          };
          if(  strlen($_POST["cecountry_search"])>0){
            $cecountry_query = trim($_POST["cecountry_search"]);
            $cecountry_prepared = "%".$cecountry_query."%"; 
            $stmt->bindParam(":cecountry_search1", $cecountry_prepared_prepared, PDO::PARAM_STR);
            $stmt->bindParam(":cecountry_search2", $cecountry_query, PDO::PARAM_STR);
          };
          $stmt->execute();                          
          
          $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          $iterator = 0;
          foreach ($data as $row) {
          $iterator += 1;
          echo  '<tr>
                  <th scope="row">'. $iterator .'</th>
                  <td>'.  $row['nom_expo'] .'</td>
                  <td>'.  $row['musee'] .'</td>
                  <td>'.  $row['date_debut'] .'</td>
                  <td>'.  $row['date_fin'] .'</td>
                  <td>'.  $row['pays'] .'</td>
                </tr>'
          ;
          }
          }
            ?>
      </tbody>
    </table>
  </body>
</html>
