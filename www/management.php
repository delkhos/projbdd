<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["handeldMuseum"])){
    header("location: index.php");
    exit;
}

$param_musee = $_SESSION["handeldMuseum"];
// Include config file
require_once "config.php";

$exponame = $exponame_err = $about = $about_err = $expo_success = $sdate = $edate = $perm_err = $piece_err = $piece_success = $piece_update_success = $piece_update_err = "";


if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST["submit1"]) && $_POST["submit1"]){
    $do = true;
    if(empty(trim($_POST["exponame"]))){
        $exponame_err = "Please enter a name.";
        $do = false;
    }else{
        $exponame = trim($_POST["exponame"]);
    }
    if($do){
      if($_POST["type_expo"] != "perm"){
        $do = true;
        if(empty(trim($_POST["sdate"]))){
            $do = false;
        }else{
            $sdate = trim($_POST["sdate"]);
        }
        if(empty(trim($_POST["edate"]))){
            $do = false;
        }else{
            $edate = trim($_POST["edate"]);
        }
        if(empty(trim($_POST["about"]))){
            $do = false;
        }else{
            $about = trim($_POST["about"]);
        }
        if($do){
        
          $sql1 = "";
          $sql2 = "";
          
          if($_POST["type_expo"] === "tempoa"){
            $sql1 = "INSERT INTO Expo_id(expo_type) VALUES ('tempoa'); " ;
            $sql2 =  " INSERT INTO exposition_temporaire_artiste(expo_id,nom_expo,musee,date_debut,date_fin,artiste) VALUES(currval('Expo_id_expo_id_seq'),:nom_expo,:museum_name,:sdate,:edate,:about) ";
          }else if($_POST["type_expo"] === "tempop"){
            $sql1 = "INSERT INTO Expo_id(expo_type) VALUES ('tempop'); " ;
            $sql2 =  " INSERT INTO exposition_temporaire_pays(expo_id,nom_expo,musee,date_debut,date_fin,pays) VALUES(currval('Expo_id_expo_id_seq'),:nom_expo,:museum_name,:sdate,:edate,:about)" ;
          }else if($_POST["type_expo"] === "tempoc"){
            $sql1 = "INSERT INTO Expo_id(expo_type) VALUES ('tempoc'); " ;
            $sql2 =  " INSERT INTO exposition_temporaire_courant(expo_id,nom_expo,musee,date_debut,date_fin,courant) VALUES(currval('Expo_id_expo_id_seq'),:nom_expo,:museum_name,:sdate,:edate,:about) ";
          }
          if($stmt = $pdo->prepare($sql2)){
              // Bind variables to the prepared statement as parameters
              $stmt->bindParam(":nom_expo", $param_expo_name, PDO::PARAM_STR);
              $stmt->bindParam(":museum_name", $param_museum_name, PDO::PARAM_STR);
              $stmt->bindParam(":sdate", $sdate, PDO::PARAM_STR);
              $stmt->bindParam(":edate", $edate, PDO::PARAM_STR);
              $stmt->bindParam(":about", $about, PDO::PARAM_STR);
              
              // Set parameters
              $param_expo_name = trim($_POST["exponame"]);
              $param_museum_name = trim($_SESSION["handeldMuseum"]);
              
              // Attempt to execute the prepared statement
              try{ 
                $stmt2 = $pdo->prepare($sql1)->execute();
                if($stmt->execute()){
                  $expo_success = "Operation was a success.";
                }else{
                  $perm_err = "About doesn't exist.";
                }
              } 
              catch(PDOException $exception){ 
                $expo_err = $exception;
              } 

              // Close statement
              unset($stmt);
          }
        }else{
          $about_err = "Some information is missing.";
        }
      }else{
        
        $sql2 = "INSERT INTO Expo_id(expo_type) VALUES ('perm')";
        $sql = "INSERT INTO exposition_permanente(expo_id,nom_expo,musee) VALUES(currval('Expo_id_expo_id_seq'),:nom_expo,:museum_name) ";
          
          if($stmt = $pdo->prepare($sql)){
              // Bind variables to the prepared statement as parameters
              $stmt->bindParam(":nom_expo", $param_expo_name, PDO::PARAM_STR);
              $stmt->bindParam(":museum_name", $param_musee_name, PDO::PARAM_STR);
              
              // Set parameters
              $param_expo_name = trim($_POST["exponame"]);
              $param_musee_name = trim($_SESSION["handeldMuseum"]);
              
              // Attempt to execute the prepared statement
              try{ 
                $stmt2 = $pdo->prepare($sql2)->execute();
                if($stmt->execute()){
                  $expo_success = "Operation was a success.";
                }else{
                  $perm_err = "About doesn't exist.";
                }
              } 
              catch(PDOException $exception){ 
                $perm_err = $exception;
              } 

              // Close statement
              unset($stmt);
          }
      }
    }
  }else if( $_POST["submit3"]){
    $sql = "UPDATE oeuvre SET nom_oeuvre=:nom ,date = :date , description=:description,type=:type,musee=:musee,exposition=:exposition, artiste=:artiste,courant_artistique=:movement  
            WHERE oeuvre_id = :id";
          
          if($stmt = $pdo->prepare($sql)){
              // Bind variables to the prepared statement as parameters
              $stmt->bindParam(":nom", $param_nom, PDO::PARAM_STR);
              $stmt->bindParam(":date", $param_date, PDO::PARAM_STR);
              $stmt->bindParam(":description", $param_description, PDO::PARAM_STR);
              $stmt->bindParam(":type", $param_type, PDO::PARAM_STR);
              $stmt->bindParam(":musee", $param_musee, PDO::PARAM_STR);
              $stmt->bindParam(":exposition", $param_expo, PDO::PARAM_STR);
              $stmt->bindParam(":artiste", $param_artiste, PDO::PARAM_STR);
              $stmt->bindParam(":movement", $param_movement, PDO::PARAM_STR);
              $stmt->bindParam(":id", $param_id, PDO::PARAM_STR);
              
              // Set parameters
              $param_nom = trim($_POST["piecenamem"]);
              $param_date = trim($_POST["datem"]);
              $param_description = trim($_POST["descriptionm"]);
              $param_type = trim($_POST["type_oeuvrem"]);
              $param_musee = trim($_SESSION["handeldMuseum"]);
              $param_expo = trim($_POST["expoidm"]);
              $param_artiste = trim($_POST["artistnamem"]);
              $param_movement = trim($_POST["movementnamem"]);
              $param_id = trim($_POST["foo"]);
              
              // Attempt to execute the prepared statement
              try{ 
                $stmt->execute();
                  $piece_update_success = "Operation was a success.";
              } 
              catch(PDOException $exception){ 
                $piece_update_err = $exception;
              } 

              // Close statement
              unset($stmt);
          }
        

  }else if( $_POST["submit2"]){
    $sql = "INSERT INTO oeuvre(nom_oeuvre,date,description,type,musee,exposition, artiste,courant_artistique) 
            VALUES(:nom,:date,:description,:type,:musee,:exposition,:artiste,:movement) ";
          
          if($stmt = $pdo->prepare($sql)){
              // Bind variables to the prepared statement as parameters
              $stmt->bindParam(":nom", $param_nom, PDO::PARAM_STR);
              $stmt->bindParam(":date", $param_date, PDO::PARAM_STR);
              $stmt->bindParam(":description", $param_description, PDO::PARAM_STR);
              $stmt->bindParam(":type", $param_type, PDO::PARAM_STR);
              $stmt->bindParam(":musee", $param_musee, PDO::PARAM_STR);
              $stmt->bindParam(":exposition", $param_expo, PDO::PARAM_STR);
              $stmt->bindParam(":artiste", $param_artiste, PDO::PARAM_STR);
              $stmt->bindParam(":movement", $param_movement, PDO::PARAM_STR);
              
              // Set parameters
              $param_nom = trim($_POST["piecename"]);
              $param_date = trim($_POST["date"]);
              $param_description = trim($_POST["description"]);
              $param_type = trim($_POST["type_oeuvre"]);
              $param_musee = trim($_SESSION["handeldMuseum"]);
              $param_expo = trim($_POST["expoid"]);
              $param_artiste = trim($_POST["artistname"]);
              $param_movement = trim($_POST["movementname"]);
              
              // Attempt to execute the prepared statement
              try{ 
                $stmt->execute();
                  $piece_success = "Operation was a success.";
              } 
              catch(PDOException $exception){ 
                $piece_err = $exception;
              } 

              // Close statement
              unset($stmt);
          }
        

  }
  unset($pdo);
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <!-- jQuery library -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script type="text/javascript">
      function enableAbout(){
        document.getElementById('about').removeAttribute('disabled')
        document.getElementById('sdate').removeAttribute('disabled')
        document.getElementById('edate').removeAttribute('disabled')
      }
    </script>
    <script type="text/javascript">
      function disableAbout(){
        document.getElementById('about').setAttribute('disabled','true')
        document.getElementById('sdate').setAttribute('disabled','true')
        document.getElementById('edate').setAttribute('disabled','true')
      }
    </script>
    <script type="text/javascript">
    function stoppedTyping(){
        if(document.getElementById('idpiece').value.length > 0){
            document.getElementById('submitmodify').disabled = false; 
        } else { 
            document.getElementById('submitmodify').disabled = true;
        }
    }
    </script>
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
          <!--
          <a class="nav-item nav-link " href="advanced-search.php">Advanced Search</a>
          -->
        <?php
          if(isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] === true){
          echo '<a class="nav-item nav-link " href="adminpanel.php">Admin Panel</a>';
          }
          if(isset($_SESSION["handeldMuseum"]) && strlen($_SESSION["handeldMuseum"]) > 0){
          echo '<a class="nav-item nav-link " href="management.php">Museum Management</a>';
          }
        ?>
        </div>
      </div>
      <form class="form-inline" method="post" action="search.php">
        <input class="form-control mr-sm-2" name="search" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
      </form>
    </div>
  </nav>
    <div class="container">
        <h3>You are handling : <b><?php echo htmlspecialchars($_SESSION["handeldMuseum"]); ?></b>.</h3>
        <h3>Create Exhibition.</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="exponame" class="form-control <?php echo (!empty($exponame_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $exponame; ?>">
                <span class="invalid-feedback"><?php echo $exponame_err; ?></span>
                <label>Type:</label><br>
                <input type="radio"  name="type_expo" value="perm" checked  onclick="disableAbout()">
                <label for="none">Permanent</label>
                <input type="radio"  name="type_expo" value="tempoa" onclick="enableAbout()">
                <label for="give">Temporary Artist</label><br>
                <input type="radio"  name="type_expo" value="tempop" onclick="enableAbout()">
                <label for="give">Temporary Country</label>
                <input type="radio"  name="type_expo" value="tempoc" onclick="enableAbout()">
                <label for="give">Temporary Movement</label><br>
                <label for="give">Start Date(year-month-day)</label>
                <input disabled type="text" id="sdate" name="sdate" class="form-control <?php echo (!empty($about_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $sdate; ?>">
                <label for="give">End Date(year-month-day)</label>
                <input disabled type="text" id="edate" name="edate" class="form-control <?php echo (!empty($about_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $edate; ?>">
                <label for="give">About</label>
                <input disabled type="text" id="about" name="about" class="form-control <?php echo (!empty($about_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $about; ?>">
                <span style"color:red"><?php echo $about_err; ?></span>
                <span style:"color:red"><?php echo $perm_err; ?></span>
                <span style="color:green"><?php echo $expo_success; ?></span> <br>
                <br>

            </div>    
            <div class="form-group">
                <input type="submit" id="submit1" name="submit1" class="btn btn-primary" value="Create">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
        </form>
    </div>    
    <div class="container">
        <h3>Create art piece.</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="piecename" >
                <label>Date</label>
                <input type="number" name="date" >
                <label>Description</label>
                <input type="text" name="description" >
                <label>Artist</label>
                <input type="text" name="artistname" >
                <label>Movement</label>
                <input type="text" name="movementname" >
                <label>Exposition</label>
                <input type="number" name="expoid" >
                <label>Type:</label><br>
                <input type="radio" id="peinture" name="type_oeuvre" value="peinture" checked>
                <label for="sculpture">Painting</label>
                <input type="radio" id="sculpture" name="type_oeuvre" value="sculpture">
                <label for="give">Sculpture</label>
                <input type="radio" id="photographie" name="type_oeuvre" value="photographie">
                <label for="give"> Photography </label> <br>
                <span style="color:red"><?php echo $piece_err; ?></span> <br>
                <span style="color:green"><?php echo $piece_success; ?></span> <br>
                <br>

            </div>    
            <div class="form-group">
                <input type="submit" id="submit2" name="submit2" class="btn btn-primary" value="Create">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
        </form>
    </div>    
    <div class="container">
        <h3>Modify art piece.</h3>
        <form action="modifypiece.php" method="post">
            <div class="form-group">
                <label>Piece id</label>
                <input type="number" id="idpiece" name="pieceid" onkeyup="stoppedTyping()">
                <span style="color:red"><?php echo $piece_update_err; ?></span> <br>
                <span style="color:green"><?php echo $piece_update_success; ?></span> <br>
            </div>    
            <div class="form-group">
                <input type="submit" id="submitmodify" disabled name="submit3" class="btn btn-primary" value="Update" >
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
        </form>
    </div>

    
    <h3>Pieces and exhibitions:</h3>
    <ul id="myTab" class="nav nav-tabs nav-fill mb-3">
        <li class="nav-item">
            <a href="#pieces" class="nav-link active" data-toggle="tab"><b>Art pieces</b></a>
        </li>
        <li class="nav-item">
            <a href="#exhibitions" class="nav-link" data-toggle="tab"><b>Exhibitions</b></a>
        </li>
    </ul>

    <div class="tab-content">
      <div class="tab-pane fade show active" id="pieces">
            <table class="table">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">Id</th>
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
                  //$content = file_get_contents('http://loripsum.net/api');
                  //echo $content;
          $stmt = $pdo->prepare("
          SELECT DISTINCT * FROM Oeuvre
          WHERE musee=:musee 
          ");
          $stmt->bindParam(":musee", $param_musee, PDO::PARAM_STR);
          $stmt->execute(); 
          $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          foreach ($data as $row) {
              echo  '<tr>
                      <th scope="row">'. $row['oeuvre_id']  .'</th>
                      <td>'.  $row['nom_oeuvre'] .'</td>
                      <td>'.  $row['date'] .'</td>
                      <td>'.  $row['type'] .'</td>
                      <td>'.  $row['description'] .'</td>
                      <td>'.  $row['musee'] .'</td>
                      <td>'.  $row['exposition'] .'</td>
                      <td>'.  $row['artiste'] .'</td>
                      <td>'.  $row['courant_artistique'] .'</td>
                    </tr>'
              ;
          }
                ?>
              </tbody>
            </table>
      </div>


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
                    <th scope="col">Id</th>
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
            WHERE musee=:musee 
            ");
            $stmt->bindParam(":musee", $param_musee, PDO::PARAM_STR);
            $stmt->execute(); 
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($data as $row) {
                echo  '<tr>
                        <th scope="row">'. $row['expo_id'] .'</th>
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
                    <th scope="col">Id</th>
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
            WHERE musee=:musee 
            ");
            $stmt->bindParam(":musee", $param_musee, PDO::PARAM_STR);
            $stmt->execute(); 
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $iterator = 0;
            foreach ($data as $row) {
                $iterator += 1;
                echo  '<tr>
                        <th scope="row">'. $row['expo_id'] .'</th>
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
                    <th scope="col">Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Museum</th>
                    <th scope="col">Start date</th>
                    <th scope="col">End date</th>
                    <th scope="col">Country</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    //$content = file_get_contents('http://loripsum.net/api');
                    //echo $content;
            $stmt = $pdo->prepare("
            SELECT DISTINCT * FROM Exposition_temporaire_pays
            WHERE musee=:musee 
            ");
            $stmt->bindParam(":musee", $param_musee, PDO::PARAM_STR);
            $stmt->execute(); 
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $iterator = 0;
            foreach ($data as $row) {
                $iterator += 1;
                echo  '<tr>
                        <th scope="row">'. $row['expo_id'] .'</th>
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
                    <th scope="col">Id</th>
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
            WHERE musee=:musee 
            ");
            $stmt->bindParam(":musee", $param_musee, PDO::PARAM_STR);
            $stmt->execute(); 
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $iterator = 0;
            foreach ($data as $row) {
                $iterator += 1;
                echo  '<tr>
                        <th scope="row">'. $row['expo_id'] .'</th>
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
    </div>


</body>
</html>
