<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
require_once "config.php";

$musee = $museum_don_err = $museum_don_success = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(empty(trim($_POST["musee"])) || empty(trim($_POST["amount"]))){
      $museum_don_err = "Please fill all fields.";
  } else{
      $sql = "INSERT INTO Don(valeur,musee,mecene) VALUES (:amount,:musee,:id)";
      
      if($stmt = $pdo->prepare($sql)){
          // Bind variables to the prepared statement as parameters
          $stmt->bindParam(":amount", $param_amount, PDO::PARAM_STR);
          $stmt->bindParam(":musee", $param_musee, PDO::PARAM_STR);
          $stmt->bindParam(":id", $param_id, PDO::PARAM_STR);
          
          // Set parameters
          $param_amount = trim($_POST["amount"]);
          $param_musee = trim($_POST["musee"]);
          $param_id = trim($_SESSION["id"]);
          
          // Attempt to execute the prepared statement
          try{ 
            $stmt->execute();
            $museum_don_success = "Operation was a success";
          } 
          catch(PDOException $exception){ 
            $museum_don_err = "This museum doesn't exist.";
          } 

          // Close statement
          unset($stmt);
      }
  }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
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
    
    <!--
    <p>
        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
    </p>
  -->
  <h4>Welcome to ARTS EXPLORER.</h4>
  <h4>If you are an art enthusiast, this website is for you.</h4>
  <h4>Have fun ! And do not hesitate to make a donation !</h4>
  <p>
  <?php
    //$content = file_get_contents('http://loripsum.net/api');
    //echo $content;
  ?>
  <div class="container">
        <h4> Make a donation.</h4>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Amount</label>
                <input type="number" name="amount" value="5"> 
                <label>€</label><br>
                <label>Museum</label>
                <input type="text" name="musee" class="form-control <?php echo (!empty($museum_don_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $musee; ?>">
                <span class="invalid-feedback"><?php echo $museum_don_err; ?></span>
                <span style="color:green"><?php echo $museum_don_success; ?></span> <br>
            </div>    
            <div class="form-group">
                <input type="submit" name="submit2" class="btn btn-primary" value="Donate">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
        </form>
    </div>
  </p>
</body>
</html>
