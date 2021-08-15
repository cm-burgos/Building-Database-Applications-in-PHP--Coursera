<?php

session_start();
require_once "pdo.php";

if ( ! isset($_SESSION['name']) ) {
    die('ACCESS DENIED');
}

// If Cancel go back to view.php
if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}

$failure = false;  // If we have no POST data

if ( isset($_POST['make']) && isset($_POST['year']) 
     && isset($_POST['mileage']) && isset($_POST['model'])) {
    
    if  ( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || 
          strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1) {
        
        $_SESSION['error'] = "All fields are required";
        header("Location: add.php");
        return;
    
    } 
    
    if (is_numeric($_POST['year']) == false){

        $_SESSION['error'] = "Year must be an integer";
        header("Location: add.php");
        return;
    } 
    
    if (is_numeric($_POST['mileage']) == false){

        $_SESSION['error'] = "Mileage must be an integer";
        header("Location: add.php");
        return;
    } 

    else {
        $sql = "INSERT INTO autos (make, model, year, mileage) VALUES (:mk, :mo, :yr, :mi)";
        //echo("<pre>\n".$sql."\n</pre>\n");
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
        ':mk' => $_POST['make'],
        ':mo' => $_POST['model'],
        ':yr' => $_POST['year'],
        ':mi' => $_POST['mileage']));
        
        $_SESSION['success'] = "Record added";
        header("Location: index.php");
        return;
    }
    
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Carolina Maria Burgos Anillo</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Welcome to Automobiles Database</h1>

<?php
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}

?>


<form method="post">
<p>Make:
<input type="text" name="make" size="60"/></p>
<p>Model:
<input type="text" name="model" size="60"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name="mileage"/></p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</form>


</div>
</body>
</html>