<?php

session_start();
require_once "pdo.php";

if ( ! isset($_SESSION['name']) ) {
    die('ACCESS DENIED');
}


if ( isset($_POST['make']) && isset($_POST['year']) 
     && isset($_POST['mileage']) && isset($_POST['model'])) {
    
    if  ( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || 
          strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1) {
        
        $_SESSION['error'] = "All fields are required";
        header("Location:edit.php?autos_id=".$_REQUEST['autos_id']);
        return;
    }

    if (is_numeric($_POST['year']) == false){

        $_SESSION['error'] = "Year must be an integer";
        header("Location:edit.php?autos_id=".$_REQUEST['autos_id']);
        return;
    } 
    
    if (is_numeric($_POST['mileage']) == false){

        $_SESSION['error'] = "Mileage must be an integer";
        header("Location:edit.php?autos_id=".$_REQUEST['autos_id']);
        return;
    } 

    else {
        $sql = "UPDATE autos SET make = :make,
            model = :model, year = :year, 
            mileage = :mileage
            WHERE autos_id = :autos_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
        ':make' => $_POST['make'],
        ':model' => $_POST['model'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage'],
        ':autos_id' => $_POST['autos_id']));
        $_SESSION['success'] = 'Record edited';
        header( 'Location: index.php' ) ;
        return;
    }

}

// Guardian: Make sure that autos_id is present
if ( ! isset($_GET['autos_id']) ) {
    $_SESSION['error'] = "Missing autos_id";
    header('Location: index.php');
    return;
  }
  
  $stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :xyz");
  $stmt->execute(array(":xyz" => $_GET['autos_id']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ( $row === false ) {
      $_SESSION['error'] = 'Bad value for autos_id';
      header( 'Location: index.php' ) ;
      return;
  }

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$mk = htmlentities($row['make']);
$mo = htmlentities($row['model']);
$yr = htmlentities($row['year']);
$mi = htmlentities($row['mileage']);
$autos_id = $row['autos_id'];


?>

<p>Edit Autos</p>
<form method="post">
<p>Make:
<input type="text" name="make" value="<?= $mk ?>"></p>
<p>Model:
<input type="text" name="model" value="<?= $mo ?>"></p>
<p>Year:
<input type="text" name="year" value="<?= $yr ?>"></p>
<p>Mileage:
<input type="text" name="mileage" value="<?= $mi ?>"></p>
<input type="hidden" name="autos_id" value="<?= $autos_id?>">
<p><input type="submit" value="Save"/>
<a href="index.php">Cancel</a></p>
</form>
