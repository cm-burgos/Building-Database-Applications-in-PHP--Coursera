<?php

session_start();
require_once "pdo.php";

if ( ! isset($_SESSION['name']) ) {
    die('Not logged in');
}

// If Cancel go back to view.php
if ( isset($_POST['cancel']) ) {
    header('Location: view.php');
    return;
}

$failure = false;  // If we have no POST data

if ( isset($_POST['make']) && isset($_POST['year']) 
     && isset($_POST['mileage'])) {
    
    if  ((is_numeric($_POST['year']) && is_numeric($_POST['mileage'])) == false){

        $_SESSION['error'] = "Mileage and year must be numeric";
        header("Location: add.php");
        return;

    } else if ( strlen($_POST['make']) < 1 ) {
        $_SESSION['error'] = "Make is required";
        header("Location: add.php");
        return;
    } else {
        $sql = "INSERT INTO autos (make, year, mileage) VALUES (:mk, :yr, :mi)";
        //echo("<pre>\n".$sql."\n</pre>\n");
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
        ':mk' => $_POST['make'],
        ':yr' => $_POST['year'],
        ':mi' => $_POST['mileage']));
         // echo('<p style="color: green;">'.htmlentities("Record inserted")."</p>\n");
        $_SESSION['success'] = "Record inserted";
        header("Location: view.php");
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
<h1>Tracking autos for <?php echo htmlentities($_SESSION['name']); ?></h1>

<?php
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}

?>


<form method="post">
<p>Make:
<input type="text" name="make" size="60"/></p>
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