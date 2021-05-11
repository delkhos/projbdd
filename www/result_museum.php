

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
          <th scope="col">Address</th>
          <th scope="col">Entrance fee</th>
          <th scope="col">Country</th>
        </tr>
      </thead>
      <tbody>
        <?php
          if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $sql = "";
            if( strlen($_POST["mname_search"])>0){
              $sql = $sql . "(nom_musee LIKE :mname_search1 OR SIMILARITY(nom_musee,:mname_search2) > 0.4) ";
            };
          
            if( strlen($_POST["mwork_search"])>0){
              if (strlen($sql)!=0){
                $sql = $sql."AND ";
              }; 
              $sql = $sql . "(nom_musee IN (SELECT DISTINCT musee FROM Oeuvre WHERE nom_oeuvre LIKE :mwork_search1 OR SIMILARITY(nom_oeuvre,:mwork_search2) > 0.4)) ";
            };
          
            if( strlen($_POST["madress_search"])>0){
              if (strlen($sql)!=0){
                $sql = $sql."AND ";
              };
              $sql = $sql . "(nom_musee LIKE :madress_search1 OR SIMILARITY(adresse,:madress_search2) > 0.4) ";
            }; 
            if( strlen($_POST["mcountry_search"])>0){
              if (strlen($sql)!=0){
                $sql = $sql."AND ";
              };   
              $sql = $sql . "(pays LIKE :mcountry_search1 OR SIMILARITY(pays,:mcoutry_search2) > 0.4) ";
          
            }; 
          
            if (strlen($sql) >0){
              $sql = "WHERE ".$sql;
            };
            $stmt = $pdo->prepare( "SELECT DISTINCT * FROM Musee ".$sql);
             
            if( strlen($_POST["mname_search"])>0){
              $mname_query = trim($_POST["mname_search"]);
              $mname_prepared = "%".$mname_query."%"; 
              $stm->bindParam(":mname_search1", $mname_prepared, PDO::PARAM_STR);
              $stmt->bindParam(":mname_search2", $mname_query, PDO::PARAM_STR);
            };
          
            
            if( strlen($_POST["mwork_search"])>0){
              $mwork_query = trim($_POST["mwork_search"]);
              $mwork_prepared = "%".$mwork_query."%"; 
              $stmt->bindParam(":mwork_search1", $mwork_prepared, PDO::PARAM_STR);
              $stmt->bindParam(":mwork_search2", $mwork_query, PDO::PARAM_STR);
            };
            
            if( strlen($_POST["madress_search"])>0){
              $madress_query = trim($_POST["madress_search"]);
              $madress_prepared = "%".$madress_query."%"; 
              $stmt->bindParam(":madress_search1", $madress_prepared, PDO::PARAM_STR);
              $stmt->bindParam(":madress_search2", $madress_query, PDO::PARAM_STR);
            };
          
            
            if( strlen($_POST["mcountry_search"])>0){
              $mcountry_query = trim($_POST["mcountry_search"]);
              $mcountry_prepared = "%".$mcountry_query."%"; 
              $stmt->bindParam(":mcountry_search1", $mcountry_prepared, PDO::PARAM_STR);
              $stmt->bindParam(":mcountry_search2", $mcountry_query, PDO::PARAM_STR);
            };
          
            $stmt->execute();
          
          $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          $iterator = 0;
          foreach ($data as $row) {
          $iterator += 1;
          echo  '<tr>
                <th scope="row">'. $iterator .'</th>
                <td>'.  $row['nom_musee'] .'</td>
                <td>'.  $row['adresse'] .'</td>
                <td>'.  $row['prix_entree'] .'</td>
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

t
