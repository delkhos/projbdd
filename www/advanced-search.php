



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
          <form class="form-inline" method="post" action="advanced-search.php">
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
                if( isset($lookup_table [$_POST["wname_search"]])){
                $sql = $sql . "(nom_oeuvre LIKE :wname_search1 OR SIMILARITY(nom_oeuvre,:wname_search2) > 0.4) ";
               }; 
                
                if(isset($lookup_table [$_POST["wmovement_search"]])){
                  if (strlen($sql)!=0){
                    $sql = $sql."AND ";
                  };
                  $sql =  $sql."(courant_artistique LIKE :wmovement_search1 OR SIMILARITY(courant_artistique,:wmovement_search2) > 0.4 ) ";
                };
                if (isset($lookup_table [$_POST["wmuseum_search"]])){
                  if (strlen($sql)!=0){
                     $sql = $sql."AND ";
                  };
                   $sql = $sql."(musee LIKE :wmusee_search1 OR SIMILARITY(courant_artistique,:wmusee_search2) > 0.4 ) ";
                  };
                if (isset($lookup_table [$_POST["wartist_search"]])){
                  if (strlen($sql)!=0){
                    $sql = $sql."AND ";
                  };
                  $sql = $sql."(artiste LIKE :wartist_search1 OR SIMILARITY(artiste,:wartist_search2) > 0.4 ) ";
                  };
                if (isset($lookup_table [$_POST["wdate_search"]])){
                  if (strlen($sql)!=0){
                    $sql = $sql."AND ";
                  };
                 };
                if (isset($lookup_table [$_POST["wtype_search"]])){
                  if (strlen($sql)!=0){
                    $sql = $sql."AND ";
                  };
                  $sql = $sql. "(type LIKE :wtype_search1)";         
                };
                if (strlen($sql) >0){
                  $sql = "WHERE ".$sql;
                }
                
                $stmt = $pdo->prepare("SELECT DISTINCT * FROM Oeuvre a join Exposition_sans_musee b ON  a.exposition = b.expo_id ".$sql);
                
                if( isset($lookup_table [$_POST["wname_search"]])){
                  $wname_query = trim($_POST["wname_search"]);
                  $wname_prepared = "%".$wname_query."%"; 
                  $stmt->bindParam(":wname_search1", $wname_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":wname_search2", $wname_query, PDO::PARAM_STR);
                }
                if( isset($lookup_table [$_POST["wmovement_search"]])){
                  $wmovement_query = trim($_POST["wmovement_search"]);
                  $wmovement_prepared = "%".$wmovement_query."%";
                  $stmt->bindParam(":wmovement_search1", $wmovement_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":wmovement_search2", $wmovement_query, PDO::PARAM_STR);
                }
                if( isset($lookup_table [$_POST["wartist_search"]])){
                  $wartist_query = trim($_POST["wartist_search"]);
                  $wartist_prepared = "%".$wartist_query."%";
                  $stmt->bindParam(":wartist_search1", $wartist_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":wartist_search2", $wartist_query, PDO::PARAM_STR);
                }
                if( isset($lookup_table [$_POST["wdate_search"]])){
                  $sql = $sql."(date = :wdate_search2) ";
                  $wdate_query = trim($_POST["wdate_search"]);
                  $wdate_prepared = intval($wdate_query);
                  $stmt->bindParam(":wdate_search2", $wdate_prepared, PDO::PARAM_STR);
                }
                if( isset($lookup_table [$_POST["wtype_search"]])){
                  $wtype_query = trim($_POST["wtype_search"]);
                  $stmt->bindParam(":wtype_search1", $wtype_query, PDO::PARAM_STR);  
                }

                if (isset($lookup_table [$_POST["wmuseum_search"]])){
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
      
        <div class="tab-pane fade" id="artists">
          <form class="form-inline" method="post" action="advanced-search.php">
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
              if ($_SERVER["REQUEST_METHOD"] == "POST"){
                //$content = file_get_contents('http://loripsum.net/api');
                //echo $content;                
                $sql = "";
                if( isset($lookup_table [$_POST["aname_search"]])){
                  $sql = $sql . "(nom_artiste LIKE :aname_search1 OR SIMILARITY(nom_artiste,:aname_search2) > 0.4) ";
                  }; 

                if(  isset($lookup_table [$_POST["amovement_search"]])){
                  if (strlen($sql)!=0){
                    $sql = $sql."AND ";
                  };
                  $sql = $sql . "( nom_artiste IN (SELECT artiste FROM participation_courant WHERE nom_courant LIKE :amovement_search1 OR SIMILARITY(nom_courant,:amovement_search2) > 0.4)) ";
                }; 
                if ( isset($lookup_table [$_POST["adate_search"]])){
                  if (strlen($sql)!=0){
                    $sql = $sql."AND ";
                  };
                  $sql = $sql."(:adate_search2 BETWEEN date_naissance ANd date_mort) ";
                };
                if( isset($lookup_table [$_POST["acountry_search"]])){
                  if (strlen($sql)!=0){
                    $sql = $sql."AND ";
                  };
                  $sql = $sql."(pays LIKE :acountry_search2) ";
                 };
                

                if (strlen($sql) >0){
                  $sql = "WHERE ".$sql;
                };
                $stmt = $pdo->prepare( "SELECT DISTINCT * FROM Artiste ".$sql);
                
                if(  isset($lookup_table [$_POST["aname_search"]])){
                  $aname_query = trim($_POST["aname_search"]);
                  $aname_prepared = "%".$aname_query."%"; 
                  $stmt->bindParam(":aname_search1", $aname_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":aname_search2", $aname_query, PDO::PARAM_STR);
                };
                if(  isset($lookup_table [$_POST["amovement_search"]])){ 
                  $amovement_query = trim($_POST["amovement_search"]);
                  $amovement_prepared = "%".$amovement_query."%"; 
                  $stmt->bindParam(":amovement_search1", $amovement_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":amovement_search2", $amovement_query, PDO::PARAM_STR);
                };
                if(  isset($lookup_table [$_POST["adate_search"]])){
                  $adate_query = trim($_POST["adate_search"]);
                  $adate_prepared = intval($adate_query);
                  $stmt->bindParam(":adate_search2", $adate_prepared, PDO::PARAM_STR);
                };
                if (  isset($lookup_table [$_POST["acountry_search"]])){
                  $adate_query = trim($_POST["adate_search"]);
                  $adate_prepared = "%".$adate_query."%";
                  $stmt->bindParam(":acountry_search2", $acountry_prepared, PDO::PARAM_STR);
                };

                  $stmt->execute();                 
 
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $iterator = 0;
        foreach ($data as $row) {
            $iterator += 1;
            echo  '<tr>
                    <th scope="row">'. $iterator .'</th>
                    <td>'.  $row['nom_artiste'] .'</td>
                    <td>'.  $row['date_naissance'] .'</td>
                    <td>'.  $row['date_mort'] .'</td>
                    <td>'.  $row['nationalite'] .'</td>
                    <td>'.  $row['biographie'] .'</td>
                  </tr>'
            ;
        }
              }
              ?>
            </tbody>
          </table>
        </div>
        <div class="tab-pane fade" id="museums">
          <form class="form-inline" method="post" action="advanced-search.php">
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
                if( isset($lookup_table [$_POST["mname_search"]])){
                  $sql = $sql . "(nom_musee LIKE :mname_search1 OR SIMILARITY(nom_musee,:mname_search2) > 0.4) ";
                };

                if( isset($lookup_table [$_POST["mwork_search"]])){
                  if (strlen($sql)!=0){
                    $sql = $sql."AND ";
                  }; 
                  $sql = $sql . "(nom_musee IN (SELECT DISTINCT musee FROM Oeuvre WHERE nom_oeuvre LIKE :mwork_search1 OR SIMILARITY(nom_oeuvre,:mwork_search2) > 0.4)) ";
                };

                if( isset($lookup_table [$_POST["madress_search"]])){
                  if (strlen($sql)!=0){
                    $sql = $sql."AND ";
                  };
                  $sql = $sql . "(nom_musee LIKE :madress_search1 OR SIMILARITY(adresse,:madress_search2) > 0.4) ";
                }; 
                if( isset($lookup_table [$_POST["mcountry_search"]])){
                  if (strlen($sql)!=0){
                    $sql = $sql."AND ";
                  };   
                  $sql = $sql . "(pays LIKE :mcountry_search1 OR SIMILARITY(pays,:mcoutry_search2) > 0.4) ";

                }; 

                if (strlen($sql) >0){
                  $sql = "WHERE ".$sql;
                };
                $stmt = $pdo->prepare( "SELECT DISTINCT * FROM Musee ".$sql);
                 
                if( isset($lookup_values[$_POST["mname_search"]])){
                  $mname_query = trim($_POST["mname_search"]);
                  $mname_prepared = "%".$mname_query."%"; 
                  $stmt->bindParam(":mname_search1", $mname_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":mname_search2", $mname_query, PDO::PARAM_STR);
                };

                
                if( isset($lookup_values[$_POST["mwork_search"]])){
                  $mwork_query = trim($_POST["mwork_search"]);
                  $mwork_prepared = "%".$mwork_query."%"; 
                  $stmt->bindParam(":mwork_search1", $mwork_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":mwork_search2", $mwork_query, PDO::PARAM_STR);
                };
                
                if( isset($lookup_values[$_POST["madress_search"]])){
                  $madress_query = trim($_POST["madress_search"]);
                  $madress_prepared = "%".$madress_query."%"; 
                  $stmt->bindParam(":madress_search1", $madress_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":madress_search2", $madress_query, PDO::PARAM_STR);
                };

                
                if( isset($lookup_values[$_POST["mcountry_search"]])){
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
              
              <form class="form-inline" method="post" action="advanced-search.php">
                <p>   name:  </p>
                <input class="form-control mr-sm-2" name="pename_search" type="search" placeholder="name" aria-label="Search">
                <p>   art piece:  </p>
                <input class="form-control mr-sm-2" name="pework_search" type="search" placeholder="art piece" aria-label="Search">
                <p>   museum:  </p>       
                <input class="form-control mr-sm-2" name="pemuseum_search" type="search" placeholder="museum" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
              </form>
    


              <table class="table">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Museum</th>
                  </tr>
                </thead>
                <tbody>
                <?php

                if ($_SERVER["REQUEST_METHOD"] == "POST"){
                $sql = "";
                if( isset($lookup_table[$_POST["pename_search"]])){
                  $sql = $sql . "(nom_expo LIKE :pename_search1 OR SIMILARITY(nom_expo,:pename_search2) > 0.4) ";
                };
                if( isset($lookup_table[$_POST["pework_search"]])){
                  $sql = $sql . "(expo_id IN (SELECT exposition FROM Oeuvre WHERE exposition LIKE :pework_search1 OR SIMILARITY(nom_expo,:pework_search2) > 0.4)) ";
                };
                if( isset($lookup_table[$_POST["pemuseum_search"]])){
                  $sql = $sql . "(musee LIKE :pemuseum_search1 OR SIMILARITY(musee,:pemuseum_search2) > 0.4) ";
                };

                if (strlen($sql) >0){
                  $sql = "WHERE ".$sql;
                };
                
                $stmt = $pdo->prepare( "SELECT DISTINCT * FROM Exposition_permanente ".$sql);
                
                if(  isset($lookup_table [$_POST["pename_search"]])){
                  $pename_query = trim($_POST["pename_search"]);
                  $pename_prepared = "%".$pename_query."%"; 
                  $stmt->bindParam(":pename_search1", $pename_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":pename_search2", $pename_query, PDO::PARAM_STR);
                };
                if( isset($lookup_table[$_POST["pework_search"]])){
                  $pework_query = trim($_POST["pework_search"]);
                  $pework_prepared = "%".$pework_query."%"; 
                  $stmt->bindParam(":pework_search1", $pework_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":pework_search2", $pework_query, PDO::PARAM_STR);
                };
                if( isset($lookup_table[$_POST["pemuseum_search"]])){
                  $pemuseum_query = trim($_POST["pemuseum_search"]);
                  $pemuseum_prepared = "%".$pemuseum_query."%"; 
                  $stmt->bindParam(":pemuseum_search1", $pemuseum_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":pemuseum_search2", $pemuseum_query, PDO::PARAM_STR);
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
                      </tr>'
                ;
            }
                }
                  ?>
                </tbody>
              </table>
            </div>
            <div class="tab-pane fade" id="tempoa">
              
              <form class="form-inline" method="post" action="advanced-search.php">
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
                if( isset($lookup_table[$_POST["aename_search"]])){
                  $sql = $sql . "(nom_expo LIKE :aename_search1 OR SIMILARITY(nom_expo,:aename_search2) > 0.4) ";
                };
                if( isset($lookup_table[$_POST["aework_search"]])){
                  $sql = $sql . "(expo_id IN (SELECT exposition FROM Oeuvre WHERE exposition LIKE :aework_search1 OR SIMILARITY(nom_expo,:aework_search2) > 0.4)) ";
                };
                if( isset($lookup_table[$_POST["aemuseum_search"]])){
                  $sql = $sql . "(musee LIKE :aemuseum_search1 OR SIMILARITY(musee,:aemuseum_search2) > 0.4) ";
                };
                if( isset($lookup_table[$_POST["aeartist_search"]])){
                  $sql = $sql . "(musee LIKE :aeartist_search1 OR SIMILARITY(musee,:aeartist_search2) > 0.4) ";
                };
                if (strlen($sql) >0){
                  $sql = "WHERE ".$sql;
                };
                
                $stmt = $pdo->prepare( "SELECT DISTINCT * FROM Exposition_temporaire_artiste ".$sql);
                
                if(  isset($lookup_table [$_POST["aename_search"]])){
                  $aename_query = trim($_POST["aename_search"]);
                  $aename_prepared = "%".$aename_query."%"; 
                  $stmt->bindParam(":aename_search1", $aename_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":aename_search2", $aename_query, PDO::PARAM_STR);
                };
                if( isset($lookup_table[$_POST["aework_search"]])){
                  $aework_query = trim($_POST["aework_search"]);
                  $aework_prepared = "%".$aework_query."%"; 
                  $stmt->bindParam(":aework_search1", $aework_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":aework_search2", $aework_query, PDO::PARAM_STR);
                };
                if( isset($lookup_table[$_POST["aemuseum_search"]])){
                  $aemuseum_query = trim($_POST["aemuseum_search"]);
                  $aemuseum_prepared = "%".$aemuseum_query."%"; 
                  $stmt->bindParam(":aemuseum_search1", $aemuseum_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":aemuseum_search2", $aemuseum_query, PDO::PARAM_STR);
                };
                if( isset($lookup_table[$_POST["aeartist_search"]])){
                  $aeartist_query = trim($_POST["aeartist_search"]);
                  $aeartist_prepared = "%".$aeartist_query."%"; 
                  $stmt->bindParam(":aeartist_search1", $aeartist_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":aeartist_search2", $aeartist_query, PDO::PARAM_STR);
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
                        <td>'.  $row['artiste'] .'</td>
                      </tr>'
                ;
            }
                }
                  ?>
                </tbody>
              </table>
            </div>
            <div class="tab-pane fade" id="tempop">
               
              <form class="form-inline" method="post" action="advanced-search.php">
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
                if( isset($lookup_table[$_POST["cename_search"]])){
                  $sql = $sql . "(nom_expo LIKE :cename_search1 OR SIMILARITY(nom_expo,:cename_search2) > 0.4) ";
                };
                if( isset($lookup_table[$_POST["cework_search"]])){
                  $sql = $sql . "(expo_id IN (SELECT exposition FROM Oeuvre WHERE exposition LIKE :cework_search1 OR SIMILARITY(nom_expo,:cework_search2) > 0.4)) ";
                };
                if( isset($lookup_table[$_POST["cemuseum_search"]])){
                  $sql = $sql . "(musee LIKE :cemuseum_search1 OR SIMILARITY(musee,:cemuseum_search2) > 0.4) ";
                };
                if( isset($lookup_table[$_POST["cecountry_search"]])){
                  $sql = $sql . "(musee LIKE :cecountry_search1 OR SIMILARITY(musee,:cecountry_search2) > 0.4) ";
                };
                if (strlen($sql) >0){
                  $sql = "WHERE ".$sql;
                };
                
                $stmt = $pdo->prepare( "SELECT DISTINCT * FROM Exposition_temporaire_pays ".$sql);
                
                if(  isset($lookup_table [$_POST["cename_search"]])){
                  $cename_query = trim($_POST["cename_search"]);
                  $cename_prepared = "%".$cename_query."%"; 
                  $stmt->bindParam(":aename_search1", $cename_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":aename_search2", $cename_query, PDO::PARAM_STR);
                };
                if( isset($lookup_table[$_POST["cework_search"]])){
                  $cework_query = trim($_POST["cework_search"]);
                  $cework_prepared = "%".$cework_query."%"; 
                  $stmt->bindParam(":aework_search1", $cework_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":aework_search2", $cework_query, PDO::PARAM_STR);
                };
                if( isset($lookup_table[$_POST["cemuseum_search"]])){
                  $cemuseum_query = trim($_POST["cemuseum_search"]);
                  $cemuseum_prepared = "%".$cemuseum_query."%"; 
                  $stmt->bindParam(":cemuseum_search1", $cemuseum_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":cemuseum_search2", $cemuseum_query, PDO::PARAM_STR);
                };
                if( isset($lookup_table[$_POST["cecountry_search"]])){
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
            </div>
            <div class="tab-pane fade" id="tempoc">
              <form class="form-inline" method="post" action="advanced-search.php">
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



              <table class="table">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Museum</th>
                    <th scope="col">Start date</th>
                    <th scope="col">End date</th>
                    <th scope="col">Movement</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
            
   
                if ($_SERVER["REQUEST_METHOD"] == "POST"){
                $sql = "";
                if( isset($lookup_table[$_POST["mename_search"]])){
                  $sql = $sql . "(nom_expo LIKE :mename_search1 OR SIMILARITY(nom_expo,:mename_search2) > 0.4) ";
                };
                if( isset($lookup_table[$_POST["mework_search"]])){
                  $sql = $sql . "(expo_id IN (SELECT exposition FROM Oeuvre WHERE exposition LIKE :mework_search1 OR SIMILARITY(nom_expo,:mework_search2) > 0.4)) ";
                };
                if( isset($lookup_table[$_POST["memuseum_search"]])){
                  $sql = $sql . "(musee LIKE :memuseum_search1 OR SIMILARITY(musee,:memuseum_search2) > 0.4) ";
                };
                if( isset($lookup_table[$_POST["memovement_search"]])){
                  $sql = $sql . "(musee LIKE :memovement_search1 OR SIMILARITY(musee,:memovement_search2) > 0.4) ";
                };
                if (strlen($sql) >0){
                  $sql = "WHERE ".$sql;
                };
                
                $stmt = $pdo->prepare( "SELECT DISTINCT * FROM Exposition_temporaire_courant ".$sql);
                
                if(  isset($lookup_table [$_POST["mename_search"]])){
                  $mename_query = trim($_POST["mename_search"]);
                  $mename_prepared = "%".$mename_query."%"; 
                  $stmt->bindParam(":mename_search1", $mename_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":mename_search2", $mename_query, PDO::PARAM_STR);
                };
                if( isset($lookup_table[$_POST["mework_search"]])){
                  $mework_query = trim($_POST["mework_search"]);
                  $mework_prepared = "%".$mework_query."%"; 
                  $stmt->bindParam(":aework_search1", $mework_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":aework_search2", $mework_query, PDO::PARAM_STR);
                };
                if( isset($lookup_table[$_POST["memuseum_search"]])){
                  $memuseum_query = trim($_POST["memuseum_search"]);
                  $memuseum_prepared = "%".$memuseum_query."%"; 
                  $stmt->bindParam(":memuseum_search1", $memuseum_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":memuseum_search2", $memuseum_query, PDO::PARAM_STR);
                };
                if( isset($lookup_table[$_POST["memovement_search"]])){
                  $memovement_query = trim($_POST["memovement_search"]);
                  $memovement_prepared = "%".$memovement_query."%"; 
                  $stmt->bindParam(":memovement_search1", $memovement_prepared_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":memovement_search2", $memovement_query, PDO::PARAM_STR);
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
                        <td>'.  $row['courant'] .'</td>
                      </tr>'
                ;
            }
                }
                  ?>
                </tbody>
              </table>
            </div>
          </div>

        </div>
        <div class="tab-pane fade" id="movements">          <form class="form-inline" method="post" action="advanced-search.php">
            <p>   name:  </p>
            <input class="form-control mr-sm-2" name="moname_search" type="search" placeholder="name" aria-label="Search">
            <p>   artist:  </p>       
            <input class="form-control mr-sm-2" name="moartist_search" type="search" placeholder="artist" aria-label="Search">
            <p>   art piece:  </p>
            <input class="form-control mr-sm-2" name="mowork_search" type="search" placeholder="art piece" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
          </form>
 
          <table class="table">
            <thead class="thead-dark">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Start year</th>
                <th scope="col">End year</th>
                <th scope="col">Description</th>
              </tr>
            </thead>
            <tbody>
              <?php
                
              if ($_SERVER["REQUEST_METHOD"] == "POST"){
                $sql = "";
                if( isset($lookup_table[$_POST["moname_search"]])){
                  $sql = $sql . "(nom_courant LIKE :mename_search1 OR SIMILARITY(nom_expo,:mename_search2) > 0.4) ";
                };
                if( isset($lookup_table[$_POST["mowork_search"]])){
                  $sql = $sql ."(nom_courant IN (SELECT courant_artistique FROM Oeuvre WHERE nom_oeuvre LIKE :mowork_search1 OR SIMILARITY(nom_oeuvre,:mowork_search2) > 0.4)) ";
                };
                if( isset($lookup_table[$_POST["moartist_search"]])){
                  $sql = $sql . "(musee LIKE :moartist_search1 OR SIMILARITY(musee,:moartist_search2) > 0.4) ";
                };
                
                if (strlen($sql) >0){
                  $sql = "WHERE ".$sql;
                };
                
                $stmt = $pdo->prepare( "SELECT DISTINCT * FROM Courant_artistique ".$sql);
                
                if(  isset($lookup_table [$_POST["moname_search"]])){
                  $moname_query = trim($_POST["moname_search"]);
                  $moname_prepared = "%".$moname_query."%"; 
                  $stmt->bindParam(":aename_search1", $moname_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":aename_search2", $moname_query, PDO::PARAM_STR);
                };
                if( isset($lookup_table[$_POST["mowork_search"]])){
                  $mowork_query = trim($_POST["mowork_search"]);
                  $mowork_prepared = "%".$mowork_query."%"; 
                  $stmt->bindParam(":aework_search1", $mowork_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":aework_search2", $mowork_query, PDO::PARAM_STR);
                };
                if( isset($lookup_table[$_POST["moartist_search"]])){
                  $moartist_query = trim($_POST["moartist_search"]);
                  $moartist_prepared = "%".$moartist_query."%"; 
                  $stmt->bindParam(":moartist_search1", $moartist_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":moartist_search2", $moartist_query, PDO::PARAM_STR);
                };
                $stmt->execute();        

        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $iterator = 0;
        foreach ($data as $row) {
            $iterator += 1;
            echo  '<tr>
                    <th scope="row">'. $iterator .'</th>
                    <td>'.  $row['nom_courant'] .'</td>
                    <td>'.  $row['date_debut'] .'</td>
                    <td>'.  $row['date_fin'] .'</td>
                    <td>'.  $row['description'] .'</td>
                  </tr>'
            ;
        }
              }
              ?>
            </tbody>
          </table>
        </div>
    </div>


  </body>
</html> 
