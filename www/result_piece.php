

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
    <h3>Search results:</h3>
    <div class="tab-content">
      <table class="table">
        <thead class="thead-dark">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Date</th>
            <th scope="col">Type</th>
            <th scope="col">Description</th>
            <th scope="col">Museum</th>
            <th scope="col">Exhibition</th>
            <th scope="col">Artist</th>
            <th scope="col">Movement</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            if($_SERVER["REQUEST_METHOD"] == "POST"){
            //$content = file_get_contents('http://loripsum.net/api');
            //echo $content;
            $sql = "";
            if( strlen($_POST["wname_search"])>0){
            $sql = $sql . "(nom_oeuvre LIKE :wname_search1 OR SIMILARITY(nom_oeuvre,:wname_search2) > 0.4) ";
            }; 
            print($_POST["wmovement_search"]);
            if( strlen($_POST["wmovement_search"])>0){
              if (strlen($sql)!=0){
                $sql = $sql."AND ";
              };
              $sql =  $sql."(courant_artistique LIKE :wmovement_search1 OR SIMILARITY(courant_artistique,:wmovement_search2) > 0.4 ) ";
            };
            if ( strlen($_POST["wmuseum_search"])>0){
              if (strlen($sql)!=0){
                 $sql = $sql."AND ";
              };
               $sql = $sql."(musee LIKE :wmusee_search1 OR SIMILARITY(courant_artistique,:wmusee_search2) > 0.4 ) ";
              };
            if ( strlen($_POST["wartist_search"])>0){
              if (strlen($sql)!=0){
                $sql = $sql."AND ";
              };
              $sql = $sql."(artiste LIKE :wartist_search1 OR SIMILARITY(artiste,:wartist_search2) > 0.4 ) ";
              };
            if ( strlen($_POST["wdate_search"])>0){
              if (strlen($sql)!=0){
                $sql = $sql."AND ";
              };
             };
            if ( strlen($_POST["wtype_search"])>0){
              if (strlen($sql)!=0){
                $sql = $sql."AND ";
              };
              $sql = $sql. "(type LIKE :wtype_search1)";         
            };
            
            if (strlen($sql) >0){
              $sql = "WHERE ".$sql;
            }
            
            $stmt = $pdo->prepare("SELECT DISTINCT * FROM Oeuvre a join Exposition_sans_musee b ON  a.exposition = b.expo_id ".$sql);
            
            if( strlen($_POST["wname_search"])>0){
              $wname_query = trim($_POST["wname_search"]);
              $wname_prepared = "%".$wname_query."%"; 
              $stmt->bindParam(":wname_search1", $wname_prepared, PDO::PARAM_STR);
              $stmt->bindParam(":wname_search2", $wname_query, PDO::PARAM_STR);
            }
            if(  strlen($_POST["wmovement_search"])>0){
              $wmovement_query = trim($_POST["wmovement_search"]);
              $wmovement_prepared = "%".$wmovement_query."%";
              $stmt->bindParam(":wmovement_search1", $wmovement_prepared, PDO::PARAM_STR);
              $stmt->bindParam(":wmovement_search2", $wmovement_query, PDO::PARAM_STR);
            }
            if(  strlen($_POST["wartist_search"])>0){
              $wartist_query = trim($_POST["wartist_search"]);
              $wartist_prepared = "%".$wartist_query."%";
              $stmt->bindParam(":wartist_search1", $wartist_prepared, PDO::PARAM_STR);
              $stmt->bindParam(":wartist_search2", $wartist_query, PDO::PARAM_STR);
            }
            if(  strlen($_POST["wdate_search"])>0){
              $sql = $sql."(date = :wdate_search2) ";
              $wdate_query = trim($_POST["wdate_search"]);
              $wdate_prepared = intval($wdate_query);
              $stmt->bindParam(":wdate_search2", $wdate_prepared, PDO::PARAM_STR);
            }
            if(  strlen($_POST["wtype_search"])>0){
              $wtype_query = trim($_POST["wtype_search"]);
              $stmt->bindParam(":wtype_search1", $wtype_query, PDO::PARAM_STR);  
            }
            
            if ( strlen($_POST["wmuseum_search"])>0){
               $wmusee_query = trim($_POST["wmuseum_search"]);
               $wmovement_prepared = "%".$wmovement_query."%";
               $stmt->bindParam(":wmusee_search1", $wmusee_prepared, PDO::PARAM_STR);
               $stmt->bindParam(":wmusee_search2", $wmusee_query, PDO::PARAM_STR);
            };
            $stmt->execute(); 
            
            
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $iterator = 0;
            foreach ($data as $row) {
            $iterator += 1;
            echo  '<tr>
                <th scope="row">'. $iterator .'</th>
                <td>'.  $row['nom_oeuvre'] .'</td>
                <td>'.  $row['date'] .'</td>
                <td>'.  $row['type'] .'</td>
                <td>'.  $row['description'] .'</td>
                <td>'.  $row['musee'] .'</td>
                <td>'.  $row['nom_expo'] .'</td>
                <td>'.  $row['artiste'] .'</td>
                <td>'.  $row['courant_artistique'] .'</td>
              </tr>'
            ;
            }
            }
            ?>
        </tbody>
      </table>
    </div>
  </body>
</html>
