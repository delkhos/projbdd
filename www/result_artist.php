<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
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
      <span class="navbar-brand mb-0 ">Logged in as: <b><?php
echo htmlspecialchars($_SESSION["username"]);
?></b>.</span>
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
        <th scope="col">Date of birth</th>
        <th scope="col">Date of death</th>
        <th scope="col">Nationality</th>
        <th scope="col">Biography</th>
      </tr>
    </thead>
    <tbody>
      <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //$content = file_get_contents('http://loripsum.net/api');
    //echo $content;                
    $sql = "";
    if (strlen($_POST["aname_search"]) > 0) {
        $sql = $sql . "(nom_artiste LIKE :aname_search1 OR SIMILARITY(nom_artiste,:aname_search2) > 0.4) ";
    }
    ;
    
    if (strlen($_POST["amovement_search"]) > 0) {
        if (strlen($sql) != 0) {
            $sql = $sql . "AND ";
        }
        ;
        $sql = $sql . "( nom_artiste IN (SELECT artiste FROM participation_courant WHERE nom_courant LIKE :amovement_search1 OR SIMILARITY(nom_courant,:amovement_search2) > 0.4)) ";
    }
    ;
    if (strlen($_POST["adate_search"]) > 0) {
        if (strlen($sql) != 0) {
            $sql = $sql . "AND ";
        }
        ;
        $sql = $sql . "(:adate_search2 BETWEEN date_naissance ANd date_mort) ";
    }
    ;
    if (strlen($_POST["acountry_search"]) > 0) {
        if (strlen($sql) != 0) {
            $sql = $sql . "AND ";
        }
        ;
        $sql = $sql . "(pays LIKE :acountry_search2) ";
    }
    ;
    
    
    if (strlen($sql) > 0) {
        $sql = "WHERE " . $sql;
    }
    ;
    $stmt = $pdo->prepare("SELECT DISTINCT * FROM Artiste " . $sql);
    
    if (strlen($_POST["aname_search"]) > 0) {
        $aname_query    = trim($_POST["aname_search"]);
        $aname_prepared = "%" . $aname_query . "%";
        $stmt->bindParam(":aname_search1", $aname_prepared,PDO::PARAM_STR);
        $stmt->bindParam(":aname_search2", $aname_query, PDO::PARAM_STR);
    }
    ;
    if (strlen($_POST["amovement_search"]) > 0) {
        $amovement_query    = trim($_POST["amovement_search"]);
        $amovement_prepared = "%" . $amovement_query . "%";
        $stmt->bindParam(":amovement_search1", $amovement_prepared, PDO::PARAM_STR);
        $stmt->bindParam(":amovement_search2", $amovement_query, PDO::PARAM_STR);
    }
    ;
    if (strlen($_POST["adate_search"]) > 0) {
        $adate_query    = trim($_POST["adate_search"]);
        $adate_prepared = intval($adate_query);
        $stmt->bindParam(":adate_search2", $adate_prepared, PDO::PARAM_STR);
    }
    ;
    if (strlen($_POST["acountry_search"]) > 0) {
        $adate_query    = trim($_POST["adate_search"]);
        $adate_prepared = "%" . $adate_query . "%";
        $stmt->bindParam(":acountry_search2", $acountry_prepared, PDO::PARAM_STR);
    }
    ;
    
    $stmt->execute();
    
    $data     = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $iterator = 0;
    foreach ($data as $row) {
        $iterator += 1;
        echo '<tr>
                <th scope="row">' . $iterator . '</th>
                <td>' . $row['nom_artiste'] . '</td>
                <td>' . $row['date_naissance'] . '</td>
                <td>' . $row['date_mort'] . '</td>
                <td>' . $row['nationalite'] . '</td>
                <td>' . $row['biographie'] . '</td>
             </tr>';
    }
}
?>
      </tbody>
    </table>
  </body>
</html> 
