<?php
// Initialize the session
session_start();
 



// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["handeldMuseum"]) || $_SERVER["REQUEST_METHOD"] != "POST"){
    header("location: index.php");
    exit;
}

// Include config file
require_once "config.php";

$sql = "SELECT * FROM oeuvre WHERE oeuvre_id=:id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":id", $id, PDO::PARAM_STR);
$id = $_POST["pieceid"];
$stmt->execute();

$data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
if( count($data) != 1 ||   $data[0]["musee"] != $_SESSION["handeldMuseum"]){ 
    header("location: management.php");
    exit;

    

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

<form action="management.php" method="post">
            <div class="form-group">
                <input type="hidden" name="foo" value = "<?php echo $_POST["pieceid"]; ?>" > <br>
                <label>Name</label>
                <input type="text" name="piecenamem" value = "<?php echo $data[0]["nom_oeuvre"]; ?>" > <br>
                <label>Date</label>
                <input type="number" name="datem"  value = "<?php echo $data[0]["date"]; ?>"><br>
                <label>Description</label>
                <input class="input-lg" type="textm" name="descriptionm" value = "<?php echo $data[0]["description"]; ?>" ><br>
                <label>Artist</label>
                <input type="text" name="artistnamem" value = "<?php echo $data[0]["artiste"]; ?>" ><br>
                <label>Movement</label>
                <input type="text" name="movementnamem"  value = "<?php echo $data[0]["courant_artistique"]; ?>"><br>
                <label>Exposition</label>
                <input type="number" name="expoidm" value = "<?php echo $data[0]["exposition"]; ?>" ><br>
                <label>Type:</label><br>
                <input type="radio" id="peinture" name="type_oeuvrem" value="peinture" <?php  if($data[0]["type"]=="peinture" ) {    echo "checked"; } ?> >
                <label for="sculpture">Painting</label>
                <input type="radio" id="sculpture" name="type_oeuvrem" value="sculpture" <?php  if($data[0]["type"]=="sculpture" ) {    echo "checked"; } ?> >
                <label for="give">Sculpture</label>
                <input type="radio" id="tempop" name="type_oeuvrem" value="photographie" <?php  if($data[0]["type"]=="photographie" ) {    echo "checked"; } ?> >
                <label for="give"> Photography </label> <br>
                <br>

            </div>    
            <div class="form-group">
                <input type="submit" id="submit3" name="submit3" class="btn btn-primary" value="Update">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
        </form>



</body>
</html>
