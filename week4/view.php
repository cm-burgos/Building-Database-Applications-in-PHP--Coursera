<?php

session_start();
require_once "pdo.php";

if ( ! isset($_SESSION['name']) ) {
    die('Not logged in');
}

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    header('Location: logout.php');
    return;
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
$stmt = $pdo->query("SELECT make, year, mileage FROM autos ORDER BY make");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h2>Automobiles</h2>

<?php
if ( isset($_SESSION['success']) ) {
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
}
?>

<p> 
    </ul>
    <?php foreach($rows as $row)
    echo '<li> ' . htmlentities($row['make']).' '.htmlentities($row['year']).'/ '.htmlentities($row['mileage']). ' </li>'; 
    ?>
    <ul>
    
</p>

<p>
    <a href="add.php">Add New</a> |
    <a href="logout.php">Logout</a>
</p>

</div>
</body>
</html>