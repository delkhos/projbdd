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
    <ul id="myTab" class="nav nav-tabs nav-fill mb-3">
      <li class="nav-item">
            <a href="#pieces" class="nav-link active" data-toggle="tab"><b>Art pieces</b></a>
      </li>
      <li class="nav-item">
            <a href="#artists" class="nav-link" data-toggle="tab"><b>Artists</b></a>
      </li>
      <li class="nav-item">
            <a href="#museums" class="nav-link" data-toggle="tab"><b>Museums</b></a>
      </li>
        <li class="nav-item">
            <a href="#exhibitions" class="nav-link" data-toggle="tab"><b>Exhibitions</b></a>
        </li>
        <li class="nav-item">
            <a href="#movements" class="nav-link" data-toggle="tab"><b>Movements</b></a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="pieces"> 
          <form class="form-inline" method="post" action="result_piece.php">
            <p>   name:  </p>
            <input class="form-control mr-sm-2" name="wname_search" type="search" placeholder="name" aria-label="Search">
            <p>   type:  </p>
            <input class="form-control mr-sm-2" name="wtype_search" type="search" placeholder="type" aria-label="Search">
            <p>   artist:  </p>      
            <input class="form-control mr-sm-2" name="wartist_search" type="search" placeholder="artist" aria-label="Search">
            <p>   artistic movement: </p>
            <input class="form-control mr-sm-2" name="wmovement_search" type="search" placeholder="movement" aria-label="Search">
            <p>   date:  </p>
            <input class="form-control mr-sm-2" name="wdate_search" type="search" placeholder="date" aria-label="Search">
            <p>   museum:  </p> 
            <input class="form-control mr-sm-2" name="wmuseum_search" type="search" placeholder="museum" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
          </form>
        </div>
      
        <div class="tab-pane fade" id="artists">
          <form class="form-inline" method="post" action="result_artist.php">
            <p>   name:  </p>      
            <input class="form-control mr-sm-2" name="aname_search" type="search" placeholder="name" aria-label="Search">
            <p>   artistic movement:  </p>
            <input class="form-control mr-sm-2" name="amovement_search" type="search" placeholder="movement" aria-label="Search">
            <p>   date:  </p>
            <input class="form-control mr-sm-2" name="adate_search" type="search" placeholder="date" aria-label="Search">
            <p>   country:  </p>
            <input class="form-control mr-sm-2" name="acountry_search" type="search" placeholder="country" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
          </form>
        </div>
        <div class="tab-pane fade" id="museums">
          <form class="form-inline" method="post" action="result_museum.php">
            <p>   name:  </p>
            <input class="form-control mr-sm-2" name="mname_search" type="search" placeholder="name" aria-label="Search">
            <p>   piece:  </p>      
            <input class="form-control mr-sm-2" name="mwork_search" type="search" placeholder="work" aria-label="Search">
            <p>   adress:  </p>
            <input class="form-control mr-sm-2" name="madress_search" type="search" placeholder="adress" aria-label="Search">
            <p>   contry:  </p>
            <input class="form-control mr-sm-2" name="mcountry_search" type="search" placeholder="date" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
          </form>
        </div>
        <!-- sub tabs pour les expos -->
        <div class="tab-pane fade" id="exhibitions">
          <ul id="myTabexpos" class="nav nav-tabs nav-fill mb-3">
              <li class="nav-item">
                  <a href="#perm" class="nav-link active" data-toggle="tab"><b>Permanent</b></a>
              </li>
              <li class="nav-item">
                  <a href="#tempoa" class="nav-link" data-toggle="tab"><b>Temporary Artist</b></a>
              </li>
              <li class="nav-item">
                  <a href="#tempop" class="nav-link" data-toggle="tab"><b>Temporary Country</b></a>
              </li>
              <li class="nav-item">
                  <a href="#tempoc" class="nav-link" data-toggle="tab"><b>Temporary Movement</b></a>
              </li>
          </ul>
          
          <div class="tab-content">
            <div class="tab-pane fade show active" id="perm">
              
              <form class="form-inline" method="post" action="result_exhibition_perm.php">
                <p>   name:  </p>
                <input class="form-control mr-sm-2" name="pename_search" type="search" placeholder="name" aria-label="Search">
                <p>   art piece:  </p>
                <input class="form-control mr-sm-2" name="pework_search" type="search" placeholder="art piece" aria-label="Search">
                <p>   museum:  </p>      
                <input class="form-control mr-sm-2" name="pemuseum_search" type="search" placeholder="museum" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
              </form>
            </div>
            <div class="tab-pane fade" id="tempoa">
              
              <form class="form-inline" method="post" action="result_exhibition_tart.php">
                <p>   name:  </p>
                <input class="form-control mr-sm-2" name="aename_search" type="search" placeholder="name" aria-label="Search">
                <p>   art piece:  </p>
                <input class="form-control mr-sm-2" name="aework_search" type="search" placeholder="art piece" aria-label="Search">
                <p>   museum:  </p>      
                <input class="form-control mr-sm-2" name="aemuseum_search" type="search" placeholder="museum" aria-label="Search">
                <p>   artist:  </p>
                <input class="form-control mr-sm-2" name="aeartist_search" type="search" placeholder="artist" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
              </form>
              
            </div>
            <div class="tab-pane fade" id="tempop">
              
              <form class="form-inline" method="post" action="result_exhibition_tcount.php">
                <p>   name:  </p>
                <input class="form-control mr-sm-2" name="cename_search" type="search" placeholder="name" aria-label="Search">
                <p>   art piece:  </p>
                <input class="form-control mr-sm-2" name="cework_search" type="search" placeholder="art piece" aria-label="Search">
                <p>   museum:  </p>      
                <input class="form-control mr-sm-2" name="cemuseum_search" type="search" placeholder="museum" aria-label="Search">
                <p>   country:  </p>
                <input class="form-control mr-sm-2" name="cecountry_search" type="search" placeholder="country" aria-label="Search">

                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
              </form>
            </div>
            <div class="tab-pane fade" id="tempoc">
              <form class="form-inline" method="post" action="result_exhibition_tmov.php">
                <p>   name:  </p>
                <input class="form-control mr-sm-2" name="mename_search" type="search" placeholder="name" aria-label="Search">
                <p>   art piece:  </p>
                <input class="form-control mr-sm-2" name="mework_search" type="search" placeholder="art piece" aria-label="Search">
                <p>   museum:  </p>      
                <input class="form-control mr-sm-2" name="memuseum_search" type="search" placeholder="museum" aria-label="Search">
                <p>   movement:  </p>
                <input class="form-control mr-sm-2" name="memovement_search" type="search" placeholder="movement" aria-label="Search">

                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
              </form>
            </div>
          </div>

        </div>
        <div class="tab-pane fade" id="movements">          <form class="form-inline" method="post" action="result_movement.php">
            <p>   name:  </p>
            <input class="form-control mr-sm-2" name="moname_search" type="search" placeholder="name" aria-label="Search">
            <p>   artist:  </p>      
            <input class="form-control mr-sm-2" name="moartist_search" type="search" placeholder="artist" aria-label="Search">
            <p>   art piece:  </p>
            <input class="form-control mr-sm-2" name="mowork_search" type="search" placeholder="art piece" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
          </form>
        </div>
    </div>
</body>
</html>
