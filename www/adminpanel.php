<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["isAdmin"]) || $_SESSION["isAdmin"] === false ){
    header("location: index.php");
    exit;
}

// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $museum = $museum_name = $adresse = $pays ="";
$username_err = $museum_err = $museum_create_err = "";
$username_success = $museum_success = $museum_create_success = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if($_POST["submit1"]){
      if(empty(trim($_POST["username"]))){
          $username_err = "Please enter a username.";
      } else{
          // Prepare a select statement
          $sql = "SELECT id FROM Users WHERE username = :username";
          
          if($stmt = $pdo->prepare($sql)){
              // Bind variables to the prepared statement as parameters
              $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
              
              // Set parameters
              $param_username = trim($_POST["username"]);
              
              // Attempt to execute the prepared statement
              if($stmt->execute()){
                  if($stmt->rowCount() == 1){
                      $username = trim($_POST["username"]);
                  } else{
                      $username_err = "This username doesn't exist.";
                  }
              } else{
                  echo "Oops! Something went wrong. Please try again later.";
              }

              // Close statement
              unset($stmt);
          }
      }
      // Check input errors before inserting in database
      if(empty($username_err) && $_POST["give_or_remove"]!=="none" ){
          
          // Prepare an insert statement
          $sql = "UPDATE Users SET admin=true WHERE username = :username";
          if($_POST["give_or_remove"]==="remove"){
            $sql = "UPDATE Users SET admin=false WHERE username = :username";
          }
           
          if($stmt = $pdo->prepare($sql)){
              // Bind variables to the prepared statement as parameters
              $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
              
              // Set parameters
              $param_username = $username;
              
              // Attempt to execute the prepared statement
              if($stmt->execute()){
                  $username_success = "Operation was a success";
              } else{
                  echo "Oops! Something went wrong. Please try again later.";
              }

              // Close statement
              unset($stmt);
          }
      }
      if(empty($username_err) && $_POST["give_or_remove_museum"]!=="none" ){
          
          // Prepare an insert statement
          $sql = "UPDATE Users SET handeld_musee=null WHERE username = :username";
          if($_POST["give_or_remove_museum"]==="give"){
            $sql = "UPDATE Users SET handeld_musee= :museum WHERE username = :username";
          }
          if($_POST["give_or_remove_museum"]==="remove" || !empty(trim($_POST["museum"]))){ 
            if($stmt = $pdo->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
                
                if($_POST["give_or_remove_museum"]==="give"){
                  $stmt->bindParam(":museum", $param_museum, PDO::PARAM_STR);
                  $param_museum = trim($_POST["museum"]);
                }  
                // Set parameters
                $param_username = $username;
                
                // Attempt to execute the prepared statement
                try{ 
                  $stmt->execute();
                  $museum_success = "Operation was a success";
                } 
                catch(PDOException $exception){ 
                  $museum_err = "Museum ".trim($_POST["museum"])." doesn't exist";
                } 


                // Close statement
                unset($stmt);
            }
          }else{
            $museum_err = "Please enter a museum.";
          }
      }
    }else if($_POST["submit2"]){
      if(empty(trim($_POST["museum_name"])) || empty(trim($_POST["adresse"])) || empty(trim($_POST["pays"]))){
          $museum_create_err = "Please fill all fields.";
      } else{
          // Prepare a select statement
          $sql = "INSERT INTO musee(nom_musee,adresse,pays) VALUES(:museum_name,:adresse,:pays) ";
          
          if($stmt = $pdo->prepare($sql)){
              // Bind variables to the prepared statement as parameters
              $stmt->bindParam(":museum_name", $param_museum_name, PDO::PARAM_STR);
              $stmt->bindParam(":pays", $param_pays, PDO::PARAM_STR);
              $stmt->bindParam(":adresse", $param_adresse, PDO::PARAM_STR);
              
              // Set parameters
              $param_museum_name = trim($_POST["museum_name"]);
              $param_adresse = trim($_POST["adresse"]);
              $param_pays = trim($_POST["pays"]);
              
              // Attempt to execute the prepared statement
              try{ 
                $stmt->execute();
                $museum_create_success = "Operation was a success";
              } 
              catch(PDOException $exception){ 
                $museum_create_err = $exception;
              } 

              // Close statement
              unset($stmt);
          }
      }
    }
    
    // Close connection
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
        <h3>Modify user.</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
                <label>Give or remove administrative rights</label><br>
                <input type="radio" id="none" name="give_or_remove" value="none" checked>
                <label for="none">None</label><br>
                <input type="radio" id="give" name="give_or_remove" value="give">
                <label for="give">Give</label><br>
                <input type="radio" id="remove" name="give_or_remove" value="remove">
                <label for="remove">Remove</label><br>
                <span style="color:green"><?php echo $username_success; ?></span> <br>
                <label>Give or remove handling of museum</label><br>
                <input type="radio" id="none" name="give_or_remove_museum" value="none" checked  onclick="document.getElementById('museum_field').setAttribute('disabled','true')">
                <label for="none">None</label><br>
                <input type="radio" id="remove" name="give_or_remove_museum" value="remove"  onclick="document.getElementById('museum_field').setAttribute('disabled','true')">
                <label for="give">Remove</label><br>
                <input type="radio" id="give" name="give_or_remove_museum" value="give" onclick="document.getElementById('museum_field').removeAttribute('disabled')">
                <label for="give">Give</label>
                <input disabled type="text" id="museum_field" name="museum" class="form-control <?php echo (!empty($museum_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $museum; ?>">
                <span class="invalid-feedback"><?php echo $museum_err; ?></span>
                <span style="color:green"><?php echo $museum_success; ?></span> <br>
                <br>

            </div>    
            <div class="form-group">
                <input type="submit" name="submit1" class="btn btn-primary" value="Update">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
        </form>
    </div>    
    <div class="container">
        <h3> Create Museum.</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="museum_name" class="form-control <?php echo (!empty($museum_create_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $museum_name; ?>">
                <label>Address</label>
                <input type="text" name="adresse" class="form-control <?php echo (!empty($museum_create_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $adresse; ?>">
                <label>Country</label>
                <input type="text" name="pays" class="form-control <?php echo (!empty($museum_create_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $pays; ?>">
                <span class="invalid-feedback"><?php echo $museum_create_err; ?></span>
                <span style="color:green"><?php echo $museum_create_success; ?></span> <br>
            </div>    
            <div class="form-group">
                <input type="submit" name="submit2" class="btn btn-primary" value="Create">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
        </form>
    </div>    
</body>
</html>
