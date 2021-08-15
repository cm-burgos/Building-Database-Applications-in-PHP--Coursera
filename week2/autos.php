<?php

require_once "pdo.php";
// Demand a GET parameter
if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
    die('Name parameter missing');
}

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}

$failure = false;  // If we have no POST data

if ( isset($_POST['make']) && isset($_POST['year']) 
     && isset($_POST['mileage'])) {
    
    if  ((is_numeric($_POST['year']) && is_numeric($_POST['mileage'])) == false){
        $failure = "Mileage and year must be numeric";
    } else if ( strlen($_POST['make']) < 1 ) {
        $failure = "Make is required";
    } else {
        $sql = "INSERT INTO autos (make, year, mileage) VALUES (:mk, :yr, :mi)";
        //echo("<pre>\n".$sql."\n</pre>\n");
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
        ':mk' => $_POST['make'],
        ':yr' => $_POST['year'],
        ':mi' => $_POST['mileage']));
         echo('<p style="color: green;">'.htmlentities("Record inserted")."</p>\n");
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
<h1>Tracking autos for <?php echo htmlentities($_REQUEST['name']); ?></h1>

<form method="post">
<p>Make:
<input type="text" name="make" size="60"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name="mileage"/></p>
<input type="submit" value="Add">
<input type="submit" name="logout" value="Logout">
</form>
<?php


if ( $failure !== false ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
}
?>
<?php
$stmt = $pdo->query("SELECT make, year, mileage FROM autos ORDER BY make");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h2>Automobiles</h2>
<p> 
    </ul>
    <?php foreach($rows as $row)
    echo '<li> ' . htmlentities($row['make']).' '.htmlentities($row['year']).'/ '.htmlentities($row['mileage']). ' </li>'; 
    ?>
    <ul>
    
</p>


</div>
</body>
</html>