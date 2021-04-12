



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
                if( strlen($_POST["wname_search"])>0){
                $sql = $sql . "(nom_oeuvre LIKE :wname_search1 OR SIMILARITY(nom_oeuvre,:wname_search2) > 0.4) ";
               }; 
                
                if(strlen($_POST["wmovement_search"])>0){
                  if (strlen($sql)!=0){
                    $sql = $sql."AND ";
                  };
                  $sql =  $sql."(courant_artistique LIKE :wmovement_search1 OR SIMILARITY(courant_artistique,:wmovement_search2) > 0.4 ) ";
                };
                if (strlen($_POST["wmuseum_search"])>0){
                  if (strlen($sql)!=0){
                     $sql = $sql."AND ";
                  };
                  $sql = $sql."(musee LIKE :wmusee_search1 OR SIMILARITY(courant_artistique,:wmusee_search2) > 0.4 ) ";
                   $wmusee_query = trim($_POST["wmuseum_search"]);
                   $wmovement_prepared = "%".$wmovement_query."%";
                   $stmt->bindParam(":wmusee_search1", $wmusee_prepared, PDO::PARAM_STR);
                   $stmt->bindParam(":wmusee_search2", $wmusee_query, PDO::PARAM_STR);
                };
                if (strlen($_POST["wartist_search"])>0){
                  if (strlen($sql)!=0){
                    $sql = $sql."AND ";
                  };
                  $sql = $sql."(artiste LIKE :wartist_search1 OR SIMILARITY(artiste,:wartist_search2) > 0.4 ) ";
                  };
                if (strlen($_POST["wdate_search"])>0){
                  if (strlen($sql)!=0){
                    $sql = $sql."AND ";
                  };
                 };
                if (strlen($_POST["wtype_search"])>0){
                  if (strlen($sql)!=0){
                    $sql = $sql."AND ";
                  };
                  $sql = $sql. "(type LIKE :wtype_search1)";         
                };
                if (strlen($sql) >0){
                  $sql = "WHERE ".$sql;
                }
                
                $stmt = $pdo->prepare("SELECT DISTINCT * FROM Oeuvre a join Exposition_sans_musee b ON  a.exposition = b.expo_id".$sql);
                
                if( strlen($_POST["wname_search"])>0){
                  $wname_query = trim($_POST["wname_search"]);
                  $wname_prepared = "%".$wname_query."%"; 
                  $stmt->bindParam(":wname_search1", $wname_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":wname_search2", $wname_query, PDO::PARAM_STR);
                }
                if( strlen($_POST["wmovement_search"])>0){
                  $wmovement_query = trim($_POST["wmovement_search"]);
                  $wmovement_prepared = "%".$wmovement_query."%";
                  $stmt->bindParam(":wmovement_search1", $wmovement_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":wmovement_search2", $wmovement_query, PDO::PARAM_STR);
                }
                if( strlen($_POST["wartist_search"])>0){
                  $wartist_query = trim($_POST["wartist_search"]);
                  $wartist_prepared = "%".$wartist_query."%";
                  $stmt->bindParam(":wartist_search1", $wartist_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":wartist_search2", $wartist_query, PDO::PARAM_STR);
                }
                if( strlen($_POST["wdate_search"])>0){
                  $sql = $sql."(date = :wdate_search2) ";
                  $wdate_query = trim($_POST["wdate_search"]);
                  $wdate_prepared = intval($wdate_query);
                  $stmt->bindParam(":wdate_search2", $wdate_prepared, PDO::PARAM_STR);
                }
                if( strlen($_POST["wtype_search"])>0){
                  $wtype_query = trim($_POST["wtype_search"]);
                  $stmt->bindParam(":wtype_search1", $wtype_query, PDO::PARAM_STR);  
                }
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
                if( $_POST["aname_search"]>0){
                  $sql = $sql . "(nom_oeuvre LIKE :aname_search1 OR SIMILARITY(nom_oeuvre,:aname_search2) > 0.4) ";
                  }; 

                if( $_POST["amovement_search"]>0){
                  if (strlen($sql)!=0){
                    $sql = $sql."AND ";
                  };
                  $sql = $sql . "(nom_oeuvre LIKE :amovement_search1 OR SIMILARITY(nom_oeuvre,:amovement_search2) > 0.4) ";
                  }; 
                if (strlen($_POST["adate_search"])>0){
                  if (strlen($sql)!=0){
                    $sql = $sql."AND ";
                  };
                  $sql = $sql."(:adate_search2 BETWEEN date_naissance ANd date_mort) ";
                  $adate_query = trim($_POST["adate_search"]);
                  $adate_prepared = intval($adate_query);
                  $stmt->bindParam(":adate_search2", $adate_prepared, PDO::PARAM_STR);
                };
                if (strlen($_POST["acounty_search"])>0){
                  if (strlen($sql)!=0){
                    $sql = $sql."AND ";
                  };
                  $sql = $sql."(pays LIKE :acountry_search2) ";
                  $adate_query = trim($_POST["adate_search"]);
                  $adate_prepared = "%".$adate_query."%";
                  $stmt->bindParam(":acountry_search2", $acountry_prepared, PDO::PARAM_STR);
                };
                

                if (strlen($sql) >0){
                  $sql = "WHERE ".$sql;
                };
                $stmt = $pdo->prepare( "SELECT DISTINCT * FROM Artiste a JOIN Participation_courant b ON b.artiste = a.nom_artiste".$sql);
                
                if( $_POST["aname_search"]>0){
                  $aname_query = trim($_POST["aname_search"]);
                  $aname_prepared = "%".$aname_query."%"; 
                  $stmt->bindParam(":aname_search1", $aname_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":aname_search2", $aname_query, PDO::PARAM_STR);
                }
                if( $_POST["amovement_search"]>0){ 
                  $amovement_query = trim($_POST["amovement_search"]);
                  $amovement_prepared = "%".$amovement_query."%"; 
                  $stmt->bindParam(":amovement_search1", $amovement_prepared, PDO::PARAM_STR);
                  $stmt->bindParam(":amovement_search2", $amovement_query, PDO::PARAM_STR);
                }
                  
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
            <input class="form-control mr-sm-2" name="mname_search" type="search" placeholder="work" aria-label="Search">
            <p>   piece:  </p>      
            <input class="form-control mr-sm-2" name="mwork_search" type="search" placeholder="work" aria-label="Search">
            <p>   adress:  </p>
            <input class="form-control mr-sm-2" name="madress_search" type="search" placeholder="adress" aria-label="Search">
            <p>   date:  </p>
            <input class="form-control mr-sm-2" name="mcountry_search" type="search" placeholder="country" aria-label="Search">
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
                //$content = file_get_contents('http://loripsum.net/api');
                //echo $content;
        $stmt = $pdo->prepare("
 = a.nom_artiste
        WHERE (nom_artiste LIKE :aname_search1 OR 
        SIMILARITY(nom_artiste,:aname_search2) > 0.4 ) AND
        (nationalite LIKE :acountry_search1 OR 
        SIMILARITY(nationalite,:acountry_search2) > 0.4) AND
        (nom_courant LIKE :amovement_search1 OR
        SIMILARITY(nom_courant,:amovement_search2) > 0.4 ) AND
        (IF :adate_search2 NOT LIKE '' THEN :adate_search1 BETWEEN a.date_naissance AND a.date_mort)
        ");
         
        $search_prepared = "%".$search_query."%";
        $stmt->bindParam(":search1", $search_prepared, PDO::PARAM_STR);
        $stmt->bindParam(":search2", $search_query, PDO::PARAM_STR);
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
                    //$content = file_get_contents('http://loripsum.net/api');
                    //echo $content;
            $stmt = $pdo->prepare("
            SELECT DISTINCT * FROM Exposition_permanente
            WHERE nom_expo LIKE :search1 OR 
            SIMILARITY(nom_expo,:search2) > 0.4 OR
            musee LIKE :search1 OR 
            SIMILARITY(musee,:search2) > 0.4
            ");
            $search_prepared = "%".$search_query."%";
            $stmt->bindParam(":search1", $search_prepared, PDO::PARAM_STR);
            $stmt->bindParam(":search2", $search_query, PDO::PARAM_STR);
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
                  ?>
                </tbody>
              </table>
            </div>
            <div class="tab-pane fade" id="tempoa">
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
                    //$content = file_get_contents('http://loripsum.net/api');
                    //echo $content;
            $stmt = $pdo->prepare("
            SELECT DISTINCT * FROM Exposition_temporaire_artiste
            WHERE nom_expo LIKE :search1 OR 
            SIMILARITY(nom_expo,:search2) > 0.4 OR
            musee LIKE :search1 OR 
            SIMILARITY(musee,:search2) > 0.4 OR
            artiste LIKE :search1 OR 
            SIMILARITY(artiste,:search2) > 0.4
            ");
            $search_prepared = "%".$search_query."%";
            $stmt->bindParam(":search1", $search_prepared, PDO::PARAM_STR);
            $stmt->bindParam(":search2", $search_query, PDO::PARAM_STR);
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
                  ?>
                </tbody>
              </table>
            </div>
            <div class="tab-pane fade" id="tempop">
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
                    //$content = file_get_contents('http://loripsum.net/api');
                    //echo $content;
            $stmt = $pdo->prepare("
            SELECT DISTINCT * FROM Exposition_temporaire_pays
            WHERE nom_expo LIKE :search1 OR 
            SIMILARITY(nom_expo,:search2) > 0.4 OR
            musee LIKE :search1 OR 
            SIMILARITY(musee,:search2) > 0.4 OR
            pays LIKE :search1 OR 
            SIMILARITY(pays,:search2) > 0.4
            ");
            $search_prepared = "%".$search_query."%";
            $stmt->bindParam(":search1", $search_prepared, PDO::PARAM_STR);
            $stmt->bindParam(":search2", $search_query, PDO::PARAM_STR);
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
                  ?>
                </tbody>
              </table>
            </div>
            <div class="tab-pane fade" id="tempoc">
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
                    //$content = file_get_contents('http://loripsum.net/api');
                    //echo $content;
            $stmt = $pdo->prepare("
            SELECT DISTINCT * FROM Exposition_temporaire_courant
            WHERE nom_expo LIKE :search1 OR 
            SIMILARITY(nom_expo,:search2) > 0.4 OR
            musee LIKE :search1 OR 
            SIMILARITY(musee,:search2) > 0.4 OR
            courant LIKE :search1 OR 
            SIMILARITY(courant,:search2) > 0.4
            ");
            $search_prepared = "%".$search_query."%";
            $stmt->bindParam(":search1", $search_prepared, PDO::PARAM_STR);
            $stmt->bindParam(":search2", $search_query, PDO::PARAM_STR);
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
            <p>   piece:  </p>
            <input class="form-control mr-sm-2" name="mopiece_search" type="search" placeholder="artist" aria-label="Search">
            <p>   date:  </p>
            <input class="form-control mr-sm-2" name="modate_search" type="search" placeholder="date" aria-label="Search">
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
                //$content = file_get_contents('http://loripsum.net/api');
                //echo $content;
        $stmt = $pdo->prepare("
        SELECT DISTINCT * FROM Courant_artistique
        WHERE nom_courant LIKE :name OR 
        SIMILARITY(nom_courant,:search2) > 0.4
        ");
        $search_prepared = "%".$search_query."%";
        $stmt->bindParam(":search1", $search_prepared, PDO::PARAM_STR);
        $stmt->bindParam(":search2", $search_query, PDO::PARAM_STR);
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
              ?>
            </tbody>
          </table>
        </div>
    </div>


  </body>
</html>



















     
